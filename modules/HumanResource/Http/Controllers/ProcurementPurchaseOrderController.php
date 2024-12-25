<?php

namespace Modules\HumanResource\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\HumanResource\Entities\Unit;
use Modules\Setting\Entities\Application;
use Illuminate\Contracts\Support\Renderable;
use Modules\HumanResource\Entities\ProcurementQuotation;
use Modules\HumanResource\Entities\ProcurementRequestItem;
use Modules\HumanResource\Entities\ProcurementGoodsReceived;
use Modules\HumanResource\Entities\ProcurementPurchaseOrder;
use Modules\HumanResource\DataTables\ProcurementPurchaseOrderDataTable;

class ProcurementPurchaseOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_purchase_order')->only(['index']);
        $this->middleware('permission:create_purchase_order', ['only' => ['create','store']]);
        $this->middleware('permission:update_purchase_order', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_purchase_order', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(ProcurementPurchaseOrderDataTable  $dataTable)
    {
        return $dataTable->render('humanresource::procurement.purchase.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $quotations = ProcurementQuotation::whereNotNull('bid_analysis_id')
                                            ->whereNull('purchase_order_id')
                                            ->get();
        $units = Unit::all();
        return view('humanresource::procurement.purchase.create', compact('quotations', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'quotation_id' => 'required',
            'location' => 'required',
            'vendor_name' => 'required',
            'address' => 'required',
            'company' => 'required',
            'material_description' => 'required',
            'unit_id' => 'required',
            'quantity' => 'required',
            'unit_price' => 'required',
            'total' => 'required',
            'total_amount' => 'required',
            'grand_total_amount' => 'required',
            'notes' => 'required',
            'authorizer_name' => 'required',
            'authorizer_title' => 'required',
            'authorizer_signature' => 'required',
            'authorizer_date' => 'required'
        ]);

        $path = '';
        if ($request->hasFile('authorizer_signature')) {
            $request_file = $request->file('authorizer_signature');
            $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
            $path = $request_file->storeAs('procurement/signature', $filename, 'public');
        }

        $purchaseData = ProcurementPurchaseOrder::create([
            'quotation_id'  => $request->quotation_id,
            'vendor_name'  => $request->vendor_name,
            'location'  => $request->location,
            'address'  => $request->address,
            'total'  => $request->total_amount,
            'discount'  => $request->discount_amount,
            'grand_total'  => $request->grand_total_amount,
            'notes'  => $request->notes,
            'authorizer_name'  => $request->authorizer_name,
            'authorizer_title'  => $request->authorizer_title,
            'authorizer_signature'  => $path,
            'authorizer_date'  => $request->authorizer_date,
        ]);

        if($purchaseData){
            if (ProcurementQuotation::where('id', $request->quotation_id)->update(['purchase_order_id' => $purchaseData->id])) {
                $materialDescription = $request->input('material_description');
                foreach ($materialDescription as $key => $description) {
                    if(!empty($description)){
                        $itemsData = [
                            'request_id'            => $purchaseData->id,
                            'item_type'             => 4,
                            'company'               => $request->input('company')[$key],
                            'material_description'  => $request->input('material_description')[$key],
                            'unit_id'               => $request->input('unit_id')[$key],
                            'quantity'              => $request->input('quantity')[$key],
                            'unit_price'            => $request->input('unit_price')[$key],
                            'total_price'           => $request->input('total')[$key] 
                        ];
                        ProcurementRequestItem::create($itemsData);
                    }
                }

                $pdfLink = $this->purchaseOrderPdfGenerate($purchaseData->id);
                ProcurementPurchaseOrder::where('id', $purchaseData->id)->update([ 'pdf_link' => $pdfLink ]);

                return redirect()->route('purchase.index')->with('success', localize('purchase_order_created_succesfully'));
            }else{
                return redirect()->route('purchase.index')->with('fail', localize('something_went_wrong'));
            }  
        }else{
            return redirect()->route('purchase.index')->with('fail', localize('something_went_wrong'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(ProcurementPurchaseOrder $purchase)
    {
        $purchase->load('requestItemsOrders');
        $units = Unit::all();
        $quotations = ProcurementQuotation::all();
        return view('humanresource::procurement.purchase.edit', compact('purchase', 'units', 'quotations'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $purchaseOrder = ProcurementPurchaseOrder::findOrFail($id);
        $signatureRule = $purchaseOrder->authorizer_signature ? '' : 'required';

        $request->validate([
            'location' => 'required',
            'address' => 'required',
            'company' => 'required',
            'material_description' => 'required',
            'unit_id' => 'required',
            'quantity' => 'required',
            'unit_price' => 'required',
            'total' => 'required',
            'total_amount' => 'required',
            'grand_total_amount' => 'required',
            'notes' => 'required',
            'authorizer_name' => 'required',
            'authorizer_title' => 'required',
            'authorizer_signature' => $signatureRule,
            'authorizer_date' => 'required'
        ]);

        if ($purchaseOrder->goods_received_id != NULL) {
            return redirect()->route('purchase.index')->with('fail', localize('the_goods_from_this_purchase_order_have_already_been_received.'));
        }else{
            $path = $purchaseOrder->authorizer_signature;
            if ($request->hasFile('authorizer_signature')) {
                Storage::delete('public/' . $purchaseOrder->authorizer_signature);
                $request_file = $request->file('authorizer_signature');
                $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
                $path = $request_file->storeAs('procurement/signature', $filename, 'public');
            }

            $purchaseOrderUpdate = $purchaseOrder->update([
                'location'  => $request->location,
                'address'  => $request->address,
                'total'  => $request->total_amount,
                'discount'  => $request->discount_amount,
                'grand_total'  => $request->grand_total_amount,
                'notes'  => $request->notes,
                'authorizer_name'  => $request->authorizer_name,
                'authorizer_title'  => $request->authorizer_title,
                'authorizer_signature'  => $path,
            ]);

            if($purchaseOrderUpdate){
                $purchaseOrder->requestItemsOrders()->delete();

                $materialDescription = $request->input('material_description');
                foreach ($materialDescription as $key => $description) {
                    if(!empty($description)){
                        $itemsData = [
                            'request_id'            => $id,
                            'item_type'             => 4,
                            'company'               => $request->input('company')[$key],
                            'material_description'  => $request->input('material_description')[$key],
                            'unit_id'               => $request->input('unit_id')[$key],
                            'quantity'              => $request->input('quantity')[$key],
                            'unit_price'            => $request->input('unit_price')[$key],
                            'total_price'           => $request->input('total')[$key] 
                        ];
                        ProcurementRequestItem::create($itemsData);
                    }
                }

                $pdfLink = $this->purchaseOrderPdfGenerate($purchaseOrder->id);
                ProcurementPurchaseOrder::where('id', $purchaseOrder->id)->update([ 'pdf_link' => $pdfLink ]);

                return redirect()->route('purchase.index')->with('success', localize('purchase_order_updated_succesfully'));
            }else{
                return redirect()->route('purchase.index')->with('fail', localize('something_went_wrong'));
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(ProcurementPurchaseOrder $purchase)
    {
        if ($purchase->goods_received_id != NULL) {
            $goods_id = ProcurementGoodsReceived::where('purchase_order_id', $purchase->id)->first();
            $goods_num = 'GR-'.sprintf('%05s', $goods_id->id);
            return response()->json(['error' => 'This purchase order already used for '.$goods_num]);
        }else{
            ProcurementQuotation::where('id', $purchase->quotation_id)->update(['purchase_order_id' => NULL]);
            $purchase->requestItemsOrders()->delete();

            if ($purchase->authorizer_signature) {
                Storage::delete('public/' . $purchase->authorizer_signature);
            }
            $purchase->delete();
            return response()->json(['success' => 'success']);
        }
    }

    public function purchaseOrderPdfGenerate($id){
        $purchase_order = ProcurementPurchaseOrder::where('id', $id)->first();
        $applicationInfo = Application::first();
        $po_no = 'PO-'.sprintf('%05s', $id);
        $purchase_order_items = ProcurementRequestItem::select('procurement_request_items.*', 'units.unit as unit_name')
                            ->leftJoin('units', 'procurement_request_items.unit_id', '=', 'units.id')
                            ->where('procurement_request_items.request_id', $id)
                            ->where('procurement_request_items.item_type', 4)
                            ->get();
        $page = view('humanresource::procurement.purchase.purchase_pdf', compact('purchase_order', 'purchase_order_items', 'applicationInfo', 'po_no'))->render();

        $pdf = PDF::loadHtml($page);
        $file_name = 'PurchaseOrderForm_' . sprintf('%05s', $id) . '.pdf';
        $pdf_path = storage_path('app/public/procurement/pdf/' . $file_name);
        $pdf->save($pdf_path);

        return 'procurement/pdf/'.$file_name;
    }

    public function getQuotationItems(Request $request){
        $quote_id = $request->input('quote_id');
        $quote_data = ProcurementQuotation::where('id', $quote_id)->first();

    	if($quote_data){
            $quotation_items = ProcurementRequestItem::select('procurement_request_items.*', 'units.unit as unit_name')
                            ->leftJoin('units', 'procurement_request_items.unit_id', '=', 'units.id')
                            ->where('procurement_request_items.request_id', $quote_id)
                            ->where('procurement_request_items.item_type', 2)
                            ->get();
            $units = Unit::all();

	    	$total_qteitems = count($quotation_items);
            $sl = 0;
            $html = '';
	    	$trow = '';
	    	$tbody = '';
	    	$total_purchase_items = '';

	    	$total_purchase_items.= '<input type="hidden" id="total_purchase_item" value="'.$total_qteitems.'"/>';

	    	$html.='<thead>
		    			<tr>
	                        <th class="text-center">'.localize("company").'<i class="text-danger">*</i></th>
	                        <th class="text-center">'.localize("description").'<i class="text-danger">*</i></th>
	                        <th class="text-center">'.localize("unit").'<i class="text-danger">*</i></th>
	                        <th class="text-center">'.localize("quantity").'<i class="text-danger">*</i></th>
	                        <th class="text-center">'.localize("price_per_unit").'<i class="text-danger">*</i></th>
	                        <th class="text-center">'.localize("total").'<i class="text-danger">*</i></th>
	                        <th class="text-center">'.localize("action").'<i class="text-danger"></i></th>
	                    </tr>
                    </thead>';

	    	foreach ($quotation_items as $quotation_item) {
	    		$unit_opts = '';
	    		$sl = $sl + 1;
	    		if(!empty($units)){
	    			foreach ($units as $unit) {
	    				$selected = '';
	    				if($quotation_item['unit_id']==$unit['id']){
	    					$selected = 'selected';
	    				}
	    				$unit_opts.= '<option value="'.$unit['id'].'" '.$selected.'>'.$unit['unit'].'</option>';
	    			}
	    		}

	    		$tr = '<td width="20%" class=""><input type="text" class="form-control" value="'.$quote_data->company_name.'" name="company[]" placeholder="'.localize("company").'" readonly/></td>

	                <td width="20%"><textarea class="form-control" name="material_description[]" id="description" rows="2" placeholder="'.localize("description").'" required>'.$quotation_item['material_description'].'</textarea></td>

	    			<td width="15%"><select name="unit_id[]" class="form-control" required=""><option value=""> Select Unit</option>'.$unit_opts.'</select> </td>

	    			<td width="12%" class=""><input type="number" onkeyup="calculate_purchase('.$sl.');" onchange="calculate_purchase('.$sl.');" id="quantity_'.$sl.'" class="form-control text-end" value="'.$quotation_item['quantity'].'" name="quantity[]" step="any" placeholder="0.00"  required  min="0"/></td>

	    			<td width="12%" width="17%" class="">
	                   <input type="number" onkeyup="calculate_purchase('.$sl.');" onchange="calculate_purchase('.$sl.');" id="price_per_unit_'.$sl.'" class="form-control text-end" name="unit_price[]" placeholder="0.00" value="'.$quotation_item['unit_price'].'" step="any"  required/>
	                </td>

	                <td width="15%" class="">
	                    <input type="text" class="form-control text-end total_item_price" readonly="" name="total[]" placeholder="0.00" value="'.$quotation_item['total_price'].'"  id="total_price_'.$sl.'"  required/>
	                </td>

	    			<td width="100"><a class="btn btn-danger btn-sm"  value="" onclick="deletePurchaseOrderItemRow(this)" ><i class="fa fa-close" aria-hidden="true"></i></a></td>';

			    $trow.='<tr>'.$tr.'</tr>';
			 }

			 $tbody.='<tbody id="purchase_order_item">'
			 			 .$total_purchase_items
						 .$trow.
					  '</tbody>';

			 $html.= $tbody;

			 $html.='<tfoot>
                            <tr>
                                <td class="text-end" colspan="5"><b>'.localize("total").':</b></td>
                                <td class="text-end">
                                    <input type="number" id="Total" class="text-end form-control" name="total_amount" placeholder="0.00" value="'.$quote_data->total.'" readonly="readonly" />
                                </td>
                                <td>
                                	<a id="purchase_order_item" class="btn btn-info btn-sm" name="purchase-order-item" onclick="addPurchaseOrderItem('."'purchase_order_item'".')"><i class="fa fa-plus-square" aria-hidden="true"></i></a>

                                    <input type="hidden" id="vendor_company_name" value="' . $quote_data->company_name . '"/>
                                </td>
                            </tr>

                            <tr>       
                                <td class="text-end" colspan="5"><b>'.localize('discount').':</b></td>
                                <td class="text-end">
                                    <input type="number" id="Discount" class="text-end form-control discount" name="discount_amount" placeholder="0.00" onkeyup="calculate_purchase()" step="any" value=""/>
                                </td>
                                <td></td>
                            </tr>

                            <tr> 
                                <td class="text-end" colspan="5"><b>'.localize('grand').' '.localize('total').':</b></td>
                                <td class="text-end">
                                    <input type="number" id="grandTotal" class="text-end form-control" name="grand_total_amount" placeholder="0.00" value="'.$quote_data->total.'" readonly="readonly" />
                                </td>
                                <td>
                                </td>
                            </tr>
                    </tfoot>';
			echo $html;
    	}
    }

    public function getQuotationInfo(Request $request){
        $quote_id = $request->input('quote_id'); 
        $quotationinfo = ProcurementQuotation::where('id', $quote_id)->first();
        echo json_encode($quotationinfo);
    }

}

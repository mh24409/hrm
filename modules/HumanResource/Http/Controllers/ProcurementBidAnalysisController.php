<?php

namespace Modules\HumanResource\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\HumanResource\Entities\Unit;
use Modules\Setting\Entities\Application;
use Illuminate\Contracts\Support\Renderable;
use Modules\HumanResource\Entities\ProcurementCommittee;
use Modules\HumanResource\Entities\ProcurementQuotation;
use Modules\HumanResource\Entities\ProcurementBidAnalysis;
use Modules\HumanResource\Entities\ProcurementRequestItem;
use Modules\HumanResource\DataTables\ProcurementBidAnalysisDataTable;
use Modules\HumanResource\Entities\ProcurementPurchaseOrder;

class ProcurementBidAnalysisController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_bid_analysis')->only(['index']);
        $this->middleware('permission:create_bid_analysis', ['only' => ['create','store']]);
        $this->middleware('permission:update_bid_analysis', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_bid_analysis', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(ProcurementBidAnalysisDataTable $dataTable)
    {
        return $dataTable->render('humanresource::procurement.bid.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $committees = ProcurementCommittee::where('type', NULL)->get();
        $units = Unit::all();
        $quotations = ProcurementQuotation::whereNull('bid_analysis_id')->get();
        return view('humanresource::procurement.bid.create', compact('committees', 'units', 'quotations'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'sba_no' => 'required|unique:procurement_bid_analyses,sba_no',
            'location' => 'required',
            'create_date' => 'required',
            'quotation_id' => 'required',
            'attachment' => 'required',
            'company' => 'required',
            'material_description' => 'required',
            'choosing_reason' => 'required',
            'remarks' => 'required',
            'unit_id' => 'required',
            'quantity' => 'required',
            'unit_price' => 'required',
            'total' => 'required',
            'total_amount' => 'required',
            'committee_id' => 'required',
            'signature' => 'required',
            'date' => 'required',
        ]);

        $path = '';
        if ($request->hasFile('attachment')) {
            $request_file = $request->file('attachment');
            $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
            $path = $request_file->storeAs('procurement/attachment', $filename, 'public');
        }

        $bidData = ProcurementBidAnalysis::create([
            'quotation_id' => $request->quotation_id,
            'create_date' => $request->create_date,
            'sba_no' => $request->sba_no,
            'location' => $request->location,
            'attachment' => $path,
            'total' => $request->total_amount,
        ]);

        if($bidData){
            if(ProcurementQuotation::where('id', $request->quotation_id)->update(['bid_analysis_id' => $bidData->id])){
                $materialDescription = $request->input('material_description');
                foreach ($materialDescription as $key => $description) {
                    if(!empty($description)){
                        $itemsData = [
                            'request_id'            => $bidData->id,
                            'item_type'             => 3,
                            'company'               => $request->input('company')[$key],
                            'material_description'  => $request->input('material_description')[$key],
                            'unit_id'               => $request->input('unit_id')[$key],
                            'quantity'              => $request->input('quantity')[$key],
                            'unit_price'            => $request->input('unit_price')[$key],
                            'total_price'           => $request->input('total')[$key],
                            'choosing_reason'       => $request->input('choosing_reason')[$key],
                            'remarks'               => $request->input('remarks')[$key],
                        ];
                        ProcurementRequestItem::create($itemsData);
                    }
                }

                $committeeDescription = $request->input('committee_id');
                foreach ($committeeDescription as $key => $committee) {
                    if(!empty($committee)){
                        $committeeName = ProcurementCommittee::findOrFail($request->input('committee_id')[$key]);
                        $committeeData = [
                            'bid_id' => $bidData->id,
                            'bid_committee_id' => $request->input('committee_id')[$key],
                            'type' => 'bid',
                            'name' => $committeeName->name,
                            'signature' => $request->input('signature')[$key],
                            'date' => $request->input('date')[$key],
                        ];
                        ProcurementCommittee::create($committeeData);
                    }
                }

                $pdfLink = $this->bidPdfGenerate($bidData->id);
                ProcurementBidAnalysis::where('id', $bidData->id)->update([ 'pdf_link' => $pdfLink ]);

                return redirect()->route('bid.index')->with('success', localize('bid_analysis_created_succesfully'));
            }else{
                return redirect()->route('bid.index')->with('fail', localize('something_went_wrong'));
            }
        }else{
            return redirect()->route('bid.index')->with('fail', localize('something_went_wrong'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('humanresource::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(ProcurementBidAnalysis  $bid)
    {
        
        $bid->load('requestItemsBids', 'bidCommittees');
        $committees = ProcurementCommittee::where('type', NULL)->get();
        $units = Unit::all();
        $quotations = ProcurementQuotation::all();

        return view('humanresource::procurement.bid.edit', compact('bid', 'committees', 'units', 'quotations'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $bid = ProcurementBidAnalysis::findOrFail($id);
        $attachmentRule = $bid->attachment ? '' : 'required';

        $request->validate([
            'location' => 'required',
            'create_date' => 'required',
            'attachment' => $attachmentRule,
            'company' => 'required',
            'material_description' => 'required',
            'choosing_reason' => 'required',
            'remarks' => 'required',
            'unit_id' => 'required',
            'quantity' => 'required',
            'unit_price' => 'required',
            'total' => 'required',
            'total_amount' => 'required',
            'committee_id' => 'required',
            'signature' => 'required',
            'date' => 'required',
        ]);

        $path = $bid->attachment;
        if ($request->hasFile('attachment')) {
            Storage::delete('public/' . $bid->attachment);
            $request_file = $request->file('attachment');
            $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
            $path = $request_file->storeAs('procurement/attachment', $filename, 'public');
        }

        $bidUpdate = $bid->update([
            'create_date' => $request->create_date,
            'location' => $request->location,
            'attachment' => $path,
            'total' => $request->total_amount,
        ]);

        if($bidUpdate){
            $bid->requestItemsBids()->delete();
            $bid->bidCommittees()->delete();

            $materialDescription = $request->input('material_description');
            foreach ($materialDescription as $key => $description) {
                if(!empty($description)){
                    $itemsData = [
                        'request_id'            => $bid->id,
                        'item_type'             => 3,
                        'company'               => $request->input('company')[$key],
                        'material_description'  => $request->input('material_description')[$key],
                        'unit_id'               => $request->input('unit_id')[$key],
                        'quantity'              => $request->input('quantity')[$key],
                        'unit_price'            => $request->input('unit_price')[$key],
                        'total_price'           => $request->input('total')[$key],
                        'choosing_reason'       => $request->input('choosing_reason')[$key],
                        'remarks'               => $request->input('remarks')[$key],
                    ];
                    ProcurementRequestItem::create($itemsData);
                }
            }

            $committeeDescription = $request->input('committee_id');
            foreach ($committeeDescription as $key => $committee) {
                if(!empty($committee)){
                    $committeeName = ProcurementCommittee::findOrFail($request->input('committee_id')[$key]);
                    $committeeData = [
                        'bid_id' => $bid->id,
                        'bid_committee_id' => $request->input('committee_id')[$key],
                        'type' => 'bid',
                        'name' => $committeeName->name,
                        'signature' => $request->input('signature')[$key],
                        'date' => $request->input('date')[$key],
                    ];
                    ProcurementCommittee::create($committeeData);
                }
            }

            $pdfLink = $this->bidPdfGenerate($bid->id);
            ProcurementBidAnalysis::where('id', $bid->id)->update([ 'pdf_link' => $pdfLink ]);

            return redirect()->route('bid.index')->with('success', localize('bid_analysis_updated_succesfully'));
        }else{
            return redirect()->route('bid.index')->with('fail', localize('something_went_wrong'));
        } 
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(ProcurementBidAnalysis $bid)
    {
        $quotation = ProcurementQuotation::where('id', $bid->quotation_id)->first();

        if ($quotation->purchase_order_id != NULL) {
            $pur_id = ProcurementPurchaseOrder::where('quotation_id', $quotation->id)->first();
            $pur_num = 'PO-'.sprintf('%05s', $pur_id->id);
            return response()->json(['error' => 'This bid analysis already used for '.$pur_num]);
        } else {

            ProcurementQuotation::where('id', $bid->quotation_id)->update([
                'bid_analysis_id' => NULL
            ]);

            $bid->bidCommittees()->delete();
            $bid->requestItemsBids()->delete();

            if ($bid->attachment) {
                Storage::delete('public/' . $bid->attachment);
            }

            $bid->delete();
            return response()->json(['success' => 'success']);
        }
    }

    public function bidPdfGenerate($id){
        $bid_analysis = ProcurementBidAnalysis::where('id', $id)->first();
        $commitee_lists = ProcurementCommittee::where('bid_id', $id)->where('type', 'bid')->get();
        $applicationInfo = Application::first();
        $slno = sprintf('%05s', $id);
        $bid_analysis_items = ProcurementRequestItem::select('procurement_request_items.*', 'units.unit as unit_name')
                            ->leftJoin('units', 'procurement_request_items.unit_id', '=', 'units.id')
                            ->where('procurement_request_items.request_id', $id)
                            ->where('procurement_request_items.item_type', 3)
                            ->get();
        $page = view('humanresource::procurement.bid.bid_pdf', compact('bid_analysis', 'commitee_lists', 'bid_analysis_items', 'applicationInfo', 'slno'))->render();

        $pdf = PDF::loadHtml($page);
        $file_name = 'BidAnalysisForm_' . sprintf('%05s', $id) . '.pdf';
        $pdf_path = storage_path('app/public/procurement/pdf/' . $file_name);
        $pdf->save($pdf_path);

        return 'procurement/pdf/'.$file_name;
    }

    public function getQuotationItems(Request $request)
    {
        $quote_id = $request->input('quote_id');
        $quote_data = ProcurementQuotation::where('id', $quote_id)->first();

        if ($quote_data) {

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
            $total_bid_items = '';

            $total_bid_items .= '<input type="hidden" id="total_bid_item" value="' . $total_qteitems . '"/>';

            $html .= '<thead>
		    			<tr>
	                        <th class="text-center">' . localize("company") . '<i class="text-danger">*</i></th>
	                        <th class="text-center">' . localize("description") . '<i class="text-danger">*</i></th>
	                        <th class="text-center">' . localize("reason_of_choosing") . '<i class="text-danger">*</i></th>
	                        <th class="text-center">' . localize("remarks") . '<i class="text-danger">*</i></th>
	                        <th class="text-center">' . localize("unit") . '<i class="text-danger">*</i></th>
	                        <th class="text-center">' . localize("quantity") . '<i class="text-danger">*</i></th>
	                        <th class="text-center">' . localize("price_per_unit") . '<i class="text-danger">*</i></th>
	                        <th class="text-center">' . localize("total") . '<i class="text-danger">*</i></th>
	                        <th class="text-center">' . localize("action") . '<i class="text-danger"></i></th>
	                    </tr>
                    </thead>';

            foreach ($quotation_items as $quotation_item) {
                $unit_opts = '';
                $sl = $sl + 1;

                if (!empty($units)) {
                    foreach ($units as $unit) {
                        $selected = '';
                        if ($quotation_item['unit_id'] == $unit['id']) {
                            $selected = 'selected';
                        }
                        $unit_opts .= '<option value="' . $unit['id'] . '" ' . $selected . '>' . $unit['unit'] . '</option>';
                    }
                }

                $tr = '<td width="12%" class=""><input type="text" class="form-control" value="' . $quote_data->company_name . '" name="company[]" placeholder="' . localize("company") . '" readonly/></td>

	                <td width="15%"><textarea class="form-control" name="material_description[]" id="description" rows="2" placeholder="' . localize("description") . '" required>' . $quotation_item['material_description'] . '</textarea></td>

	    			<td width="13%" class=""><input type="text" class="form-control" value="' . $quotation_item['choosing_reason'] . '" name="choosing_reason[]" placeholder="' . localize("reason_of_choosing") . '" required/></td>

	    			<td width="13%" class=""><input type="text" class="form-control" value="' . $quotation_item['remarks'] . '" name="remarks[]" placeholder="' . localize("remarks") . '" required/></td>

	    			<td width="10%"><select name="unit_id[]" class="form-control" required=""><option value=""> Select Unit</option>' . $unit_opts . '</select> </td>

	    			<td width="8%" class=""><input type="number" onkeyup="calculate_bid(' . $sl . ');" onchange="calculate_bid(' . $sl . ');" id="quantity_' . $sl . '" class="form-control text-end" value="' . $quotation_item['quantity'] . '"  name="quantity[]" placeholder="0.00" step="any" required  min="0"/></td>

	    			<td width="10%" class="">
	                   <input type="number" onkeyup="calculate_bid(' . $sl . ');" onchange="calculate_bid(' . $sl . ');" id="price_per_unit_' . $sl . '" class="form-control text-end" name="unit_price[]" placeholder="0.00" value="' . $quotation_item['unit_price'] . '" step="any"  required/>

	                </td>

	                <td width="12%" class="">
	                    <input type="text" class="form-control text-end total_item_price" readonly="" name="total[]" placeholder="0.00" value="' . $quotation_item['total_price'] . '"  id="total_price_' . $sl . '"  required/>
	                </td>

	    			<td width="100"><a  id="add_bid_item" class="btn btn-info btn-sm" name="add-bid-item" onClick="addBidItem(' . "'bid_analysis_item'" . ')"><i class="fa fa-plus-square" aria-hidden="true"></i></a> <a class="btn btn-danger btn-sm mt-1 mt-lg-0"  value="" onclick="deleteBidItemRow(this)" ><i class="fa fa-trash" aria-hidden="true"></i></a></td>';

                $trow .= '<tr>' . $tr . '</tr>';
            }

            $tbody .= '<tbody id="bid_analysis_item">'
                . $total_bid_items
                . $trow .
                '</tbody>';

            $html .= $tbody;

            $html .= '<tfoot>
                            <tr>

                                <td class="text-end" colspan="7"><b>' . localize("total") . ':</b></td>
                                <td class="text-end">

                                    <input type="number" id="Total" class="text-end form-control" name="total_amount" placeholder="0.00" value="' . $quote_data->total . '" readonly="readonly" />

                                </td>
                                <td>
                                    <input type="hidden" id="vendor_company_name" value="' . $quote_data->company_name . '"/>
                                </td>
                            </tr>
                    </tfoot>';
            echo $html;
        }
    }

    public function getCommittee(Request $request)
    {
        $commitee_id = $request->input('commitee_id');
        $committeeinfo = ProcurementCommittee::where('id', $commitee_id)->first();
        echo json_encode($committeeinfo);
    }

}

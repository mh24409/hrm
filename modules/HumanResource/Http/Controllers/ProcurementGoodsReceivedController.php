<?php

namespace Modules\HumanResource\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccCoa;
use Illuminate\Support\Facades\Storage;
use Modules\HumanResource\Entities\Unit;
use Modules\Setting\Entities\Application;
use Illuminate\Contracts\Support\Renderable;
use Modules\Accounts\Http\Traits\AccVoucherTrait;
use Modules\Accounts\Entities\AccPredefineAccount;
use Modules\HumanResource\Entities\ProcurementQuotation;
use Modules\HumanResource\Entities\ProcurementRequestItem;
use Modules\HumanResource\Entities\ProcurementGoodsReceived;
use Modules\HumanResource\Entities\ProcurementPurchaseOrder;
use Modules\HumanResource\DataTables\ProcurementGoodsReceivedDataTable;

class ProcurementGoodsReceivedController extends Controller
{
    use AccVoucherTrait;

    public function __construct()
    {
        $this->middleware('permission:read_goods_received')->only(['index']);
        $this->middleware('permission:create_goods_received', ['only' => ['create','store']]);
        $this->middleware('permission:update_goods_received', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_goods_received', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(ProcurementGoodsReceivedDataTable  $dataTable)
    {
        return $dataTable->render('humanresource::procurement.goods.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $purchase_orders = ProcurementPurchaseOrder::whereNull('goods_received_id')->get();
        $pay_types = AccCoa::where('head_level', 4)
            ->orWhere('is_bank_nature', 1)
            ->orWhere('is_cash_nature', 1)
            ->orderBy('account_name', 'ASC')->get();

        $units = Unit::all();
        return view('humanresource::procurement.goods.create', compact('purchase_orders', 'pay_types', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'purchase_order_id' => 'required',
            'vendor_name' => 'required',
            'vendor_id' => 'required',
            'created_date' => 'required',
            'material_description' => 'required',
            'unit_id' => 'required',
            'quantity' => 'required',
            'unit_price' => 'required',
            'total' => 'required',
            'sub_total' => 'required',
            'grand_total_amount' => 'required',
            'received_by_name' => 'required',
            'received_by_title' => 'required',
            'received_by_signature' => 'required',
        ]);

        $path = '';
        if ($request->hasFile('received_by_signature')) {
            $request_file = $request->file('received_by_signature');
            $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
            $path = $request_file->storeAs('procurement/signature', $filename, 'public');
        }

        $quantities = collect($request->input('quantity'));
        $totalQuantity = $quantities->sum();

        $goodsData = ProcurementGoodsReceived::create([
            'purchase_order_id' => $request->purchase_order_id,
            'vendor_name' => $request->vendor_name,
            'vendor_id' => $request->vendor_id,
            'total_quantity' => $totalQuantity,
            'total' => $request->sub_total,
            'discount' => $request->discount_amount,
            'grand_total' => $request->grand_total_amount,
            'received_by_signature' => $path,
            'received_by_name' => $request->received_by_name,
            'received_by_title' => $request->received_by_title,
        ]);

        if ($goodsData) {
            if (ProcurementPurchaseOrder::where('id', $request->purchase_order_id)->update(['goods_received_id' => $goodsData->id])) {

                $materialDescription = $request->input('material_description');
                foreach ($materialDescription as $key => $description) {
                    if (!empty($description)) {
                        $itemsData = [
                            'request_id'            => $goodsData->id,
                            'item_type'             => 5,
                            'material_description'  => $request->input('material_description')[$key],
                            'unit_id'               => $request->input('unit_id')[$key],
                            'quantity'              => $request->input('quantity')[$key],
                            'unit_price'            => $request->input('unit_price')[$key],
                            'total_price'           => $request->input('total')[$key]
                        ];
                        ProcurementRequestItem::create($itemsData);
                    }
                }

                $pdfLink = $this->goodsReceivedPdfGenerate($goodsData->id);
                $goodsData->update(['pdf_link' => $pdfLink]);

                return redirect()->route('goods.index')->with('success', localize('goods_received_succesfully'));
            } else {
                return redirect()->route('goods.index')->with('fail', localize('something_went_wrong'));
            }
        } else {
            return redirect()->route('goods.index')->with('fail', localize('something_went_wrong'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(ProcurementGoodsReceived $goods)
    {
        $goods->load('requestItemsReceives');
        $units = Unit::all();
        return view('humanresource::procurement.goods.show', compact('goods', 'units'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(ProcurementGoodsReceived  $goods)
    {
        //if voucher is approved then can't be delete "now skip this part"

        ProcurementPurchaseOrder::where('id', $goods->purchase_order_id)->update(['goods_received_id' => NUll]);
        $goods->requestItemsReceives()->delete();
        if ($goods->received_by_signature) {
            Storage::delete('public/' . $goods->received_by_signature);
        }
        $goods->delete();
        return response()->json(['success' => 'success']);
    }

    public function goodsReceivedPdfGenerate($id)
    {
        $goods_received = ProcurementGoodsReceived::where('id', $id)->first();
        $applicationInfo = Application::first();
        $invoice_no = 'INV-' . sprintf('%05s', $id);
        $goods_received_items = ProcurementRequestItem::select('procurement_request_items.*', 'units.unit as unit_name')
            ->leftJoin('units', 'procurement_request_items.unit_id', '=', 'units.id')
            ->where('procurement_request_items.request_id', $id)
            ->where('procurement_request_items.item_type', 5)
            ->get();

        $page = view('humanresource::procurement.goods.goods_pdf', compact('goods_received', 'goods_received_items', 'applicationInfo', 'invoice_no'))->render();

        $pdf = PDF::loadHtml($page);
        $file_name = 'GoodsReceivedForm_' . sprintf('%05s', $id) . '.pdf';
        $pdf_path = storage_path('app/public/procurement/pdf/' . $file_name);
        $pdf->save($pdf_path);

        return 'procurement/pdf/' . $file_name;
    }

    public function getPurchaseItems(Request $request)
    {
        $purchase_order_id = $request->input('purchase_order_id');
        $purchase_order_data = ProcurementPurchaseOrder::where('id', $purchase_order_id)->first();

        if ($purchase_order_data) {
            $purchase_items = ProcurementRequestItem::select('procurement_request_items.*', 'units.unit as unit_name')
                ->leftJoin('units', 'procurement_request_items.unit_id', '=', 'units.id')
                ->where('procurement_request_items.request_id', $purchase_order_id)
                ->where('procurement_request_items.item_type', 4)
                ->get();

            $units = Unit::all();

            $total_purchaseitems = count($purchase_items);
            $sl = 0;
            $unit_price_total = 0;
            $html = '';
            $trow = '';
            $tbody = '';
            $total_good_rcv_items = '';
            $total_good_rcv_items .= '<input type="hidden" id="total_good_rcv_items" value="' . $total_purchaseitems . '"/>';

            $html .= '<thead>
                    <tr>
                        <th class="text-center">' . localize('description') . '<i class="text-danger">*</i></th>
                        <th class="text-center">' . localize('unit') . '<i class="text-danger">*</i></th>
                        <th class="text-center">' . localize('quantity') . '<i class="text-danger">*</i></th>
                        <th class="text-center">' . localize('price_per_unit') . '<i class="text-danger">*</i></th>
                        <th class="text-center">' . localize('total') . '<i class="text-danger">*</i></th>
                        <th class="text-center">' . localize('action') . '<i class="text-danger"></i></th>
                    </tr>
                </thead>';

            foreach ($purchase_items as $purchase_item) {
                $unit_opts = '';
                $sl = $sl + 1;
                $unit_price_total = $unit_price_total + $purchase_item['quantity'];

                if (!empty($units)) {
                    foreach ($units as $unit) {
                        $selected = '';
                        if ($purchase_item['unit_id'] == $unit['id']) {
                            $selected = 'selected';
                        }
                        $unit_opts .= '<option value="' . $unit['id'] . '" ' . $selected . '>' . $unit['unit'] . '</option>';
                    }
                }

                $tr = '<td width="25%"><textarea class="form-control" name="material_description[]" id="description" rows="2" placeholder="' . localize('description') . '" required>' . $purchase_item['material_description'] . '</textarea></td>

                    <td width="20%"><select name="unit_id[]" class="form-control" required=""><option value=""> Select Unit</option>' . $unit_opts . '</select> </td>

                    <td width="17%" class="">
                        <input type="number" onkeyup="calculate_good_receive(' . $sl . ');" onchange="calculate_good_receive(' . $sl . ');" id="quantity_' . $sl . '" class="form-control text-end" value="' . $purchase_item['quantity'] . '" name="quantity[]" placeholder="0.00"  required  min="0"/>
                    </td>

                    <td width="17%" width="17%" class="">
                        <input type="number" onkeyup="calculate_good_receive(' . $sl . ');" onchange="calculate_good_receive(' . $sl . ');" id="price_per_unit_' . $sl . '" class="form-control text-end sub_total_item_price" name="unit_price[]" placeholder="0.00" value="' . $purchase_item['unit_price'] . '"  required/>
                    </td>

                    <td width="15%" class="">
                        <input type="text" class="form-control text-end total_item_price" readonly="" name="total[]" placeholder="0.00" value="' . $purchase_item['total_price'] . '"  id="total_price_' . $sl . '"  required/>
                    </td>

                    <td width="100">
                        <a class="btn btn-danger btn-sm"  value="" onclick="deleteGoodRecvItemRow(this)" ><i class="fa fa-close" aria-hidden="true"></i></a>
                    </td>';

                $trow .= '<tr>' . $tr . '</tr>';
            }

            $tbody .= '<tbody id="good_received_item">'
                . $total_good_rcv_items
                . $trow .
                '</tbody>';

            $html .= $tbody;

            $html .= '<tfoot>
                    <tr>
                        <td class="text-end" colspan="4"><b>' . localize('total') . ':</b></td>
                        <td class="text-end">
                            <input type="number" id="Total" class="text-end form-control" name="sub_total" placeholder="0.00" value="' . $purchase_order_data->total . '" readonly="readonly"/>
                        </td>
                        <td>
                            <a id="good_received_item" class="btn btn-info btn-sm" name="good-received-item"" onclick="addGoodRecvItem(' . "'good_received_item'" . ')"><i class="fa fa-plus-square" aria-hidden="true"></i></a>
                            <input type="hidden" id="vendor_company_name" value="' . $purchase_order_data->company_name . '"/>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-end" colspan="4"><b>' . localize('discount') . ':</b></td>
                        <td class="text-end">
                            <input type="number" id="Discount" class="text-end form-control discount" name="discount_amount" placeholder="0.00" onkeyup="calculate_good_receive()" value="' . $purchase_order_data->discount . '" readonly="readonly" />
                        </td>
                        <td></td>
                        </tr>

                        <tr>
                            <td class="text-end" colspan="4"><b>' . localize('grand') . ' ' . localize('total') . ':</b></td>
                            <td class="text-end">
                                <input type="number" id="grandTotal" class="text-end form-control" name="grand_total_amount" placeholder="0.00" value="' . $purchase_order_data->grand_total . '" readonly="readonly" />
                            </td>
                            <td></td>
                        </tr>
            </tfoot>';
            echo $html;
        }
    }

    public function getPurchaseInfo(Request $request)
    {
        $purchase_order_id = $request->input('purchase_order_id');
        $purchase_order_data = ProcurementPurchaseOrder::where('id', $purchase_order_id)->first();
        $quote_data = ProcurementQuotation::where('id', $purchase_order_data->quotation_id)->first();
        echo json_encode($quote_data);
    }
}

<?php

namespace Modules\HumanResource\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HumanResource\Entities\Unit;
use Illuminate\Contracts\Support\Renderable;
use Modules\HumanResource\Entities\ProcurementVendor;
use Modules\HumanResource\Entities\ProcurementRequest;
use Modules\HumanResource\Entities\ProcurementQuotation;
use Modules\HumanResource\Entities\ProcurementRequestItem;
use Modules\HumanResource\DataTables\ProcurementQuotationDataTable;
use Illuminate\Support\Facades\Storage;
use Modules\HumanResource\Entities\ProcurementBidAnalysis;
use Modules\Setting\Entities\Application;

class ProcurementQuotationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_quotation')->only(['index']);
        $this->middleware('permission:create_quotation', ['only' => ['create','store']]);
        $this->middleware('permission:update_quotation', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_quotation', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(ProcurementQuotationDataTable $dataTable)
    {
        return $dataTable->render('humanresource::procurement.quotation.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($quation)
    {
        $procurementRequest = ProcurementRequest::with('requestItems')->findOrFail($quation);
        $vendors = ProcurementVendor::all();
        $units = Unit::all();

        return view('humanresource::procurement.quotation.create', compact('vendors', 'units', 'procurementRequest'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'request_id' => 'required',
            'vendor_id' => 'required',
            'address' => 'required',
            'pin_or_equivalent' => 'required|unique:procurement_quotations,pin_or_equivalent',
            'material_description' => 'required|array',
            'unit_id' => 'required|array',
            'quantity' => 'required|array',
            'unit_price' => 'required|array',
            'total' => 'required|array',
            'total_price' => 'required',
            'expected_delivery_date' => 'required',
            'delivery_place' => 'required',
            'signature' => 'required|file',
        ]);

        $path = '';
        if ($request->hasFile('signature')) {
            $request_file = $request->file('signature');
            $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
            $path = $request_file->storeAs('procurement/signature', $filename, 'public');
        }

        $vendor = ProcurementVendor::findOrFail($request->vendor_id);

        $quotationData = ProcurementQuotation::create([
            'request_id' => $request->request_id,
            'company_name' => $vendor->name,
            'vendor_id' => $request->vendor_id,
            'address' => $request->address,
            'pin_or_equivalent' => $request->pin_or_equivalent,
            'expected_delivery_date' => $request->expected_delivery_date,
            'delivery_place' => $request->delivery_place,
            'signature' => $path,
            'total' => $request->total_price,
        ]);

        if($quotationData){
            if(ProcurementRequest::where('id', $request->request_id)->update([ 'is_quoted' => 1 ])){

                $materialDescription = $request->input('material_description');
                foreach ($materialDescription as $key => $description) {
                    if(!empty($description)){
                        $itemsData = [
                            'request_id'            => $quotationData->id,
                            'item_type'             => 2,
                            'material_description'  => $request->input('material_description')[$key],
                            'unit_id'               => $request->input('unit_id')[$key],
                            'quantity'              => $request->input('quantity')[$key],
                            'unit_price'            => $request->input('unit_price')[$key],
                            'total_price'           => $request->input('total')[$key],
                        ];
                        ProcurementRequestItem::create($itemsData);
                    }
                }

                $pdfLink = $this->quotationPdfGenerate($quotationData->id);
                ProcurementQuotation::where('id', $quotationData->id)->update([ 'pdf_link' => $pdfLink ]);

                return redirect()->route('quotation.index')->with('success', localize('quotation_created_succesfully'));
            }else{
                return redirect()->route('quotation.index')->with('fail', localize('something_went_wrong'));
            }
        }else{
            return redirect()->route('quotation.index')->with('fail', localize('something_went_wrong'));
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
    public function edit(ProcurementQuotation $quotation)
    {
        $procurementRequest = ProcurementQuotation::with('requestItems')->findOrFail($quotation->id);
        $vendors = ProcurementVendor::all();
        $units = Unit::all();

        return view('humanresource::procurement.quotation.edit', compact('vendors', 'units', 'procurementRequest'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $quotation = ProcurementQuotation::findOrFail($id);
        $signatureRule = $quotation->signature ? '' : 'required';

        $request->validate([
            'address' => 'required',
            'material_description' => 'required|array',
            'unit_id' => 'required|array',
            'quantity' => 'required|array',
            'unit_price' => 'required|array',
            'total' => 'required|array',
            'total_price' => 'required',
            'expected_delivery_date' => 'required',
            'delivery_place' => 'required',
            'signature' => $signatureRule,
        ]);

        if ($quotation->bid_analysis_id != NULL) {
            $bid_id = ProcurementBidAnalysis::where('quotation_id', $quotation->id)->first();
            $bid_analysis_num = 'BID-'.sprintf('%05s', $bid_id->id);
            return redirect()->route('quotation.index')->with('fail', localize('this_quote_is_already_used_for').' '.$bid_analysis_num);
        }else{
            $path = $quotation->signature;
            if ($request->hasFile('signature')) {
                // Delete previous signature if it exists
                Storage::delete('public/' . $quotation->signature);

                $request_file = $request->file('signature');
                $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
                $path = $request_file->storeAs('procurement/signature', $filename, 'public');
            }

            $quotationUpdate = $quotation->update([
                'address' => $request->address,
                'expected_delivery_date' => $request->expected_delivery_date,
                'delivery_place' => $request->delivery_place,
                'signature' => $path,
                'total' => $request->total_price,
            ]);

            if($quotationUpdate){
                // Delete existing related records
                $quotation->requestItems()->delete();

                $materialDescription = $request->input('material_description');
                foreach ($materialDescription as $key => $description) {
                    if(!empty($description)){
                        $itemsData = [
                            'request_id'            => $quotation->id,
                            'item_type'             => 2,
                            'material_description'  => $request->input('material_description')[$key],
                            'unit_id'               => $request->input('unit_id')[$key],
                            'quantity'              => $request->input('quantity')[$key],
                            'unit_price'            => $request->input('unit_price')[$key],
                            'total_price'           => $request->input('total')[$key],
                        ];
                        ProcurementRequestItem::create($itemsData);
                    }
                }

                $pdfLink = $this->quotationPdfGenerate($quotation->id);
                ProcurementQuotation::where('id', $quotation->id)->update([ 'pdf_link' => $pdfLink ]);
        
                return redirect()->route('quotation.index')->with('success', localize('quotation_updated_succesfully'));
            }else{
                return redirect()->route('quotation.index')->with('fail', localize('something_went_wrong'));
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(ProcurementQuotation  $quotation)
    {
        if ($quotation->bid_analysis_id != NULL) {
            $bid_id = ProcurementBidAnalysis::where('quotation_id', $quotation->id)->first();
            $bid_analysis_num = 'BID-'.sprintf('%05s', $bid_id->id);
            return response()->json(['error' => 'This quote is already used for '.$bid_analysis_num]);
        }else{
            $quotation->requestItems()->delete();
            ProcurementRequest::where('id', $quotation->request_id)->update([
                'is_quoted' => 0
            ]);

            if ($quotation->signature) {
                Storage::delete('public/' . $quotation->signature);
            }

            $quotation->delete();
            return response()->json(['success' => 'success']);
        }
    }

    public function quotationPdfGenerate($id){
        $quotation = ProcurementQuotation::where('id', $id)->first();
        $applicationInfo = Application::first();
        $slno = sprintf('%05s', $id);
        $quotation_items = ProcurementRequestItem::select('procurement_request_items.*', 'units.unit as unit_name')
                            ->leftJoin('units', 'procurement_request_items.unit_id', '=', 'units.id')
                            ->where('procurement_request_items.request_id', $id)
                            ->where('procurement_request_items.item_type', 2)
                            ->get();

        $page = view('humanresource::procurement.quotation.quotation_pdf', compact('quotation', 'quotation_items', 'applicationInfo', 'slno'))->render();

        $pdf = PDF::loadHtml($page);
        $file_name = 'QuotationForm_' . sprintf('%05s', $id) . '.pdf';
        $pdf_path = storage_path('app/public/procurement/pdf/' . $file_name);
        $pdf->save($pdf_path);

        return 'procurement/pdf/'.$file_name;
    }
}

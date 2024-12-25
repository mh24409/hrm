<?php

namespace Modules\HumanResource\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HumanResource\Entities\Unit;
use Modules\Setting\Entities\Application;
use Illuminate\Contracts\Support\Renderable;
use Modules\HumanResource\Entities\Employee;
use Modules\HumanResource\Entities\Department;
use Modules\HumanResource\Entities\ProcurementRequest;
use Modules\HumanResource\Entities\ProcurementQuotation;
use Modules\HumanResource\Entities\ProcurementRequestItem;
use Modules\HumanResource\DataTables\ProcurementRequestDataTable;

class ProcurementRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_request')->only(['index']);
        $this->middleware('permission:create_request', ['only' => ['create','store']]);
        $this->middleware('permission:update_request', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_request', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(ProcurementRequestDataTable  $dataTable)
    {
        return $dataTable->render('humanresource::procurement.request.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $employees = Employee::where('is_supervisor', 1)
        ->get(['id', 'first_name', 'last_name', 'middle_name']);

        $departments = Department::whereNull('parent_id')->where('is_active', true)->get();
        $units = Unit::all();
        return view('humanresource::procurement.request.create', compact('employees', 'departments', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id'           => 'required',
            'department_id'         => 'required',
            'expected_start_date'   => 'required',
            'expected_end_date'     => 'required',
            'request_reason'        => 'required',
            'material_description'  => 'required',
            'unit_id'               => 'required',
            'quantity'              => 'required',
        ]);

        $requestData = ProcurementRequest::create([
            'employee_id'           => $request->employee_id,
            'department_id'         => $request->department_id,
            'expected_start_date'   => $request->expected_start_date,
            'expected_end_date'     => $request->expected_end_date,
            'request_reason'        => $request->request_reason,
        ]);

        if($requestData){
            $materialDescription = $request->input('material_description');
            foreach ($materialDescription as $key => $description) {
                if(!empty($description)){
                    $itemsData = [
                        'request_id'            => $requestData->id,
                        'item_type'             => 1,
                        'material_description'  => $request->input('material_description')[$key],
                        'unit_id'               => $request->input('unit_id')[$key],
                        'quantity'              => $request->input('quantity')[$key],
                    ];
                    ProcurementRequestItem::create($itemsData);
                }
            }

            $pdfLink = $this->requestPdfGenerate($requestData->id);
            ProcurementRequest::where('id', $requestData->id)->update([ 'pdf_link' => $pdfLink ]);

            return redirect()->route('procurement_request.index')->with('success', localize('request_created_succesfully'));
        }else{
            return redirect()->route('procurement_request.index')->with('fail', localize('something_went_wrong'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(ProcurementRequest $req)
    {
        // Eager load the related models
        $req->load('requestItems');

        $employees = Employee::where('is_supervisor', 1)
        ->get(['id', 'first_name', 'last_name', 'middle_name']);
        $departments = Department::whereNull('parent_id')->where('is_active', true)->get();
        $units = Unit::all();

        return view('humanresource::procurement.request.show', compact('req', 'employees', 'departments', 'units'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(ProcurementRequest $req)
    {
        // Eager load the related models
        $req->load('requestItems');

        $employees = Employee::where('is_supervisor', 1)
        ->get(['id', 'first_name', 'last_name', 'middle_name']);
        $departments = Department::whereNull('parent_id')->where('is_active', true)->get();
        $units = Unit::all();

        return view('humanresource::procurement.request.edit', compact('req', 'employees', 'departments', 'units'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'employee_id'           => 'required',
            'department_id'         => 'required',
            'expected_start_date'   => 'required',
            'expected_end_date'     => 'required',
            'request_reason'        => 'required',
            'material_description'  => 'required',
            'unit_id'               => 'required',
            'quantity'              => 'required',
        ]);

        $procurementRequest = ProcurementRequest::findOrFail($id);

        if ($procurementRequest->is_quoted != NULL) {
            $qt_id = ProcurementQuotation::where('request_id', $id)->first();
            $qt_num = 'QT-'.sprintf('%05s', $qt_id->id);
            return redirect()->route('procurement_request.index')->with('fail', localize('this_request_already_used_for').' '.$qt_num);
        }else{

            $procurementRequestUpdate = $procurementRequest->update([
                'employee_id'           => $request->employee_id,
                'department_id'         => $request->department_id,
                'expected_start_date'   => $request->expected_start_date,
                'expected_end_date'     => $request->expected_end_date,
                'request_reason'        => $request->request_reason,
            ]);

            if($procurementRequestUpdate){
                // Delete existing related records
                $procurementRequest->requestItems()->delete();

                $materialDescription = $request->input('material_description');
                foreach ($materialDescription as $key => $description) {
                    if(!empty($description)){
                        $itemsData = [
                            'request_id'            => $procurementRequest->id,
                            'item_type'             => 1,
                            'material_description'  => $request->input('material_description')[$key],
                            'unit_id'               => $request->input('unit_id')[$key],
                            'quantity'              => $request->input('quantity')[$key],
                        ];
                        ProcurementRequestItem::create($itemsData);
                    }
                }

                $pdfLink = $this->requestPdfGenerate($procurementRequest->id);
                ProcurementRequest::where('id', $procurementRequest->id)->update([ 'pdf_link' => $pdfLink ]);

                return redirect()->route('procurement_request.index')->with('success', localize('request_updated_succesfully'));
            }else{
                return redirect()->route('procurement_request.index')->with('fail', localize('something_went_wrong'));
            }
        }
    }

    public function approveRequest(Request $request, $id)
    {
        $request->validate([
            'employee_id'           => 'required',
            'department_id'         => 'required',
            'expected_start_date'   => 'required',
            'expected_end_date'     => 'required',
            'request_reason'        => 'required',
            'material_description'  => 'required',
            'unit_id'               => 'required',
            'quantity'              => 'required',
            'approval_reason'       => 'required',
        ]);

        $procurementRequest = ProcurementRequest::findOrFail($id);
        // Delete existing related records
        $procurementRequest->requestItems()->delete();

        $procurementRequest->update([
            'employee_id'           => $request->employee_id,
            'department_id'         => $request->department_id,
            'expected_start_date'   => $request->expected_start_date,
            'expected_end_date'     => $request->expected_end_date,
            'request_reason'        => $request->request_reason,
            'approval_reason'       => $request->approval_reason,
            'is_approve'    		=> 1,
        ]);

        $materialDescription = $request->input('material_description');
        foreach ($materialDescription as $key => $description) {
            if(!empty($description)){
                $itemsData = [
                    'request_id'            => $procurementRequest->id,
                    'item_type'             => 1,
                    'material_description'  => $request->input('material_description')[$key],
                    'unit_id'               => $request->input('unit_id')[$key],
                    'quantity'              => $request->input('quantity')[$key],
                ];
                ProcurementRequestItem::create($itemsData);
            }
        }

        return redirect()->route('procurement_request.index')->with('success', localize('request_approved_succesfully'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(ProcurementRequest $req)
    {
        if ($req->is_quoted != NULL) {
            $qt_id = ProcurementQuotation::where('request_id', $req->id)->first();
            $qt_num = 'QT-'.sprintf('%05s', $qt_id->id);
            return response()->json(['error' => 'This request already used for '.$qt_num]);
        }else{
            $req->requestItems()->delete();
            $req->delete();
            return response()->json(['success' => 'success']);
        }
    }

    public function requestPdfGenerate($id){
        $request = ProcurementRequest::select('procurement_requests.*', 'e.first_name', 'e.last_name', 'd.department_name')
                                    ->leftJoin('employees as e', 'procurement_requests.employee_id', '=', 'e.id')
                                    ->leftJoin('departments as d', 'procurement_requests.department_id', '=', 'd.id')
                                    ->where('procurement_requests.id', $id)
                                    ->first();

        $applicationInfo = Application::first();
        $slno = sprintf('%05s', $id);
        $request_items = ProcurementRequestItem::select('procurement_request_items.*', 'units.unit as unit_name')
                            ->leftJoin('units', 'procurement_request_items.unit_id', '=', 'units.id')
                            ->where('procurement_request_items.request_id', $id)
                            ->where('procurement_request_items.item_type', 1)
                            ->get();

        $page = view('humanresource::procurement.request.request_pdf', compact('request', 'request_items', 'applicationInfo', 'slno'))->render();

        $pdf = PDF::loadHtml($page);
        $file_name = 'RequestForm_' . sprintf('%05s', $id) . '.pdf';
        $pdf_path = storage_path('app/public/procurement/pdf/' . $file_name);
        $pdf->save($pdf_path);

        return 'procurement/pdf/'.$file_name;
    }
}

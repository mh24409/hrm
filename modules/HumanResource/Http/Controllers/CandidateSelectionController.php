<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Accounts\Entities\AccSubcode;
use Modules\HumanResource\DataTables\CandidateSelectionDataTable;
use Modules\HumanResource\Entities\CandidateInformation;
use Modules\HumanResource\Entities\CandidateInterview;
use Modules\HumanResource\Entities\CandidateSelection;
use Modules\HumanResource\Entities\Employee;
use Modules\HumanResource\Entities\Position;

class CandidateSelectionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:create_candidate_selection'])->only('store');
        $this->middleware(['permission:update_candidate_selection'])->only('update');
        $this->middleware(['permission:delete_candidate_selection'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(CandidateSelectionDataTable  $dataTable)
    {
        return $dataTable->render('humanresource::recruitment.selection.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $candidates = CandidateInterview::join('candidate_information', 'candidate_interviews.candidate_id', '=', 'candidate_information.id')
                    ->where('candidate_interviews.selection', 1)
                    ->select('candidate_information.id', 'candidate_information.first_name', 'candidate_information.last_name')
                    ->get();

        $positions  = Position::all();

        return view('humanresource::recruitment.selection.create', compact('candidates', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'candidate_id'      => 'required',
            'position_id'       => 'required',
            'selection_terms'   => 'required'
        ]);

        $candidate = CandidateInformation::findOrFail($request->candidate_id);

        // Check if candidate email matches any employee email
        $employee = Employee::where('email', $candidate->email)->first();

        if ($employee) {
            return response()->json(['error' => true, 'msg' => 'Candidate is already an employee.']);
        } else {

            $user = new User();
            $user->user_type_id = 2;
            $user->user_name = strtolower($candidate->first_name . ' ' . $candidate->last_name);
            $user->full_name = strtolower($candidate->first_name . ' ' . $candidate->last_name);
            $user->email = $candidate->email;
            $user->contact_no = $candidate->phone;
            $user->password = Hash::make(12345678);
            $user->is_active = true;
            $user->save();
            $user->assignRole(2);

            $newEmployeeData = [
                'user_id'           => $user->id,
                'first_name'        => $candidate->first_name,
                'last_name'         => $candidate->last_name,
                'position_id'       => $request->position_id,
                'email'             => $candidate->email,
                'phone'             => $candidate->phone,
                'profile_image'     => $candidate->picture,
                'alternate_phone'   => $candidate->alternative_phone,
                'present_address'   => $candidate->present_address,
                'permanent_address' => $candidate->permanent_address,
                'ssn'               => $candidate->ssn,
                'state_id'          => $candidate->country_id,
                'city'              => $candidate->city,
                'zip'               => $candidate->zip,
            ];

            $employee = Employee::create($newEmployeeData);

            $accSubCode = [
                'acc_subtype_id'    => 1,
                'name'              => $candidate->first_name . ' ' . $candidate->last_name,
                'reference_no'      => $employee->id,
                'created_by'        => auth()->user()->id,
            ];

            AccSubcode::create($accSubCode);

            $requestData = $request->all();
            $requestData['employee_id'] = $employee->id;

            if (CandidateSelection::create($requestData)) {
                return response()->json(['error' => false, 'msg' => 'Candidate Selection created successfully!']);
            } else {
                return response()->json(['error' => true, 'msg' => 'Something Went Wrong']);
            }
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
    public function edit(CandidateSelection  $selection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, CandidateSelection  $selection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(CandidateSelection $selection)
    {
        $selection->delete();
        return response()->json(['success' => 'success']);
    }

    public function getPosition(Request $request)
    {
        $candidate = CandidateInterview::where('candidate_id', $request->candidate_id)->firstOrFail();
        $position = Position::findOrFail($candidate->position_id);

        return response()->json([
            'position_id' => $position->id,
            'position_name' => $position->position_name
        ]);
    }
}

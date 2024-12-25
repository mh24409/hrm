<?php

namespace Modules\HumanResource\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HumanResource\DataTables\CandidateInterviewDataTable;
use Modules\HumanResource\Entities\CandidateInterview;
use Modules\HumanResource\Entities\CandidateShortlist;
use Modules\HumanResource\Entities\Position;

class CandidateInterviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:create_interview'])->only('store');
        $this->middleware(['permission:update_interview'])->only('update');
        $this->middleware(['permission:delete_interview'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(CandidateInterviewDataTable $dataTable)
    {
        return $dataTable->render('humanresource::recruitment.interview.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $candidates = CandidateShortlist::join('candidate_information', 'candidate_shortlists.candidate_id', '=', 'candidate_information.id')
        ->select('candidate_information.id', 'candidate_information.first_name', 'candidate_information.last_name')
        ->get();

        $positions  = Position::all();

        return view('humanresource::recruitment.interview.create', compact('candidates', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'candidate_id' => 'required',
            'position_id' => 'required',
            'interview_date' => 'required',
            'interviewer' => 'required',
            'interview_marks' => 'required',
            'written_marks' => 'required',
            'mcq_marks' => 'required',
            'total_marks' => 'required',
            'selection' => 'required',
        ]);

        if (CandidateInterview::create($request->all())) {
            return response()->json(['error' => false, 'msg' => 'Interview created successfully!']);
        }
        else {
            return response()->json(['error' => true, 'msg' => 'Something Went Wrong']);
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
    public function edit(CandidateInterview  $interview)
    {
        $candidates = CandidateShortlist::join('candidate_information', 'candidate_shortlists.candidate_id', '=', 'candidate_information.id')
        ->select('candidate_information.id', 'candidate_information.first_name', 'candidate_information.last_name')
        ->get();
        $position = Position::where('id', $interview->position_id)->first();

        return view('humanresource::recruitment.interview.edit', compact('interview', 'candidates', 'position'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, CandidateInterview  $interview)
    {
        $request->validate([
            'candidate_id' => 'required',
            'position_id' => 'required',
            'interview_date' => 'required',
            'interviewer' => 'required',
            'interview_marks' => 'required',
            'written_marks' => 'required',
            'mcq_marks' => 'required',
            'total_marks' => 'required',
            'selection' => 'required',
        ]);

        if ($interview->update($request->all())) {
            return response()->json(['error' => false, 'msg' => 'Interview updated successfully!']);
        }
        else {
            return response()->json(['error' => true, 'msg' => 'Something Went Wrong']);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(CandidateInterview  $interview)
    {
        $interview->delete();
        return response()->json(['success' => 'success']);
    }

    public function getPosition(Request $request)
    {
        $candidate = CandidateShortlist::where('candidate_id', $request->candidate_id)->firstOrFail();
        $position = Position::findOrFail($candidate->position_id);

        return response()->json([
            'position_id' => $position->id,
            'position_name' => $position->position_name,
            'interview_date' => $candidate->interview_date
        ]);
    }
}

<?php

namespace Modules\HumanResource\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HumanResource\DataTables\CandidateShortlistDataTable;
use Modules\HumanResource\Entities\CandidateInformation;
use Modules\HumanResource\Entities\CandidateShortlist;
use Modules\HumanResource\Entities\Position;

class CandidateShortlistController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:create_candidate_shortlist'])->only('store');
        $this->middleware(['permission:update_candidate_shortlist'])->only('update');
        $this->middleware(['permission:delete_candidate_shortlist'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(CandidateShortlistDataTable $dataTable)
    {
        return $dataTable->render('humanresource::recruitment.shortlist.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $shortlistedCandidateIds = CandidateShortlist::pluck('candidate_id')->all();
        $candidates = CandidateInformation::whereNotIn('id', $shortlistedCandidateIds)->get();
        $positions  = Position::all();

        return view('humanresource::recruitment.shortlist.create', compact('candidates', 'positions'));
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
            'shortlist_date' => 'required',
            'interview_date' => 'required'
        ]);

        if (CandidateShortlist::create($request->all())) {
            return response()->json(['error' => false, 'msg' => 'Shortlist created successfully!']);
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
        return view('humanresource::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(CandidateShortlist $shortlist)
    {
        $candidates = CandidateInformation::all();
        $positions  = Position::all();

        return view('humanresource::recruitment.shortlist.edit', compact('shortlist', 'candidates', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, CandidateShortlist $shortlist)
    {
        $request->validate([
            'candidate_id' => 'required',
            'position_id' => 'required',
            'shortlist_date' => 'required',
            'interview_date' => 'required'
        ]);

        if ($shortlist->update($request->all())) {
            return response()->json(['error' => false, 'msg' => 'Shortlist updated successfully!']);
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
    public function destroy(CandidateShortlist $shortlist)
    {
        $shortlist->delete();
        return response()->json(['success' => 'success']);
    }
}

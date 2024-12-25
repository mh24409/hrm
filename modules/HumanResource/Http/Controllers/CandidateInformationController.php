<?php

namespace Modules\HumanResource\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HumanResource\DataTables\CandidateInformationTable;
use Modules\HumanResource\Entities\CandidateEducation;
use Modules\HumanResource\Entities\CandidateInformation;
use Modules\HumanResource\Entities\CandidateWorkExperience;
use Modules\Setting\Entities\Country;
use Illuminate\Support\Facades\Storage;

class CandidateInformationController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:create_candidate_list'])->only('store');
        $this->middleware(['permission:update_candidate_list'])->only('update');
        $this->middleware(['permission:delete_candidate_list'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(CandidateInformationTable $dataTable)
    {
        return $dataTable->render('humanresource::recruitment.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $countries = Country::all();
        return view('humanresource::recruitment.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $path = '';
        $request->validate([
            'first_name'    => 'required',
            'phone'         => 'required',
        ]);

        if ($request->hasFile('picture')) {
            $request_file = $request->file('picture');
            $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
            $path = $request_file->storeAs('candidate', $filename, 'public');
        }

        $candidate = CandidateInformation::create([
            'first_name'        => $request->first_name,
            'last_name'         => $request->last_name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'alternative_phone' => $request->alternative_phone,
            'ssn'               => $request->ssn,
            'present_address'   => $request->present_address,
            'permanent_address' => $request->permanent_address,
            'country_id'        => $request->country_id,
            'city'              => $request->city,
            'zip'               => $request->zip,
            'picture'           => $path,
        ]);

        $educationInfo = $request->input('degree');
        foreach ($educationInfo as $key => $degree) {
            if(!empty($degree)){
                $educationRecord = [
                    'candidate_id'  => $candidate->id,
                    'degree'        => $degree,
                    'university'    => $request->input('university')[$key],
                    'cgpa'          => $request->input('cgpa')[$key],
                    'comments'      => $request->input('comments')[$key],
                ];

                CandidateEducation::create($educationRecord);
            }
        }

        $workExpInfo = $request->input('company_name');
        foreach ($workExpInfo as $key => $company_name) {
            if(!empty($company_name)){
                $workExpRecord = [
                    'candidate_id'  => $candidate->id,
                    'company_name'  => $company_name,
                    'working_period'=> $request->input('working_period')[$key],
                    'duties'        => $request->input('duties')[$key],
                    'supervisor'    => $request->input('supervisor')[$key],
                ];

                CandidateWorkExperience::create($workExpRecord);
            }
        }

        return redirect()->route('candidate.index')->with('success', localize('candidate_create_succesfully'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(CandidateInformation $candidate)
    {
        // Eager load the related models
        $candidate->load('educations', 'workExperiences');
        $country = Country::where('id', $candidate->country_id)->select('country_name')->get();

        return view('humanresource::recruitment.show', compact('candidate', 'country'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(CandidateInformation $candidate)
    {
        // Eager load the related models
        $candidate->load('educations', 'workExperiences');

        $countries = Country::all();
        return view('humanresource::recruitment.edit', compact('candidate', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $path = '';
        $request->validate([
            'first_name'    => 'required',
            'phone'         => 'required',
        ]);

        $candidate = CandidateInformation::findOrFail($id);

        // Delete existing related records
        $candidate->educations()->delete();
        $candidate->workExperiences()->delete();

        $path = $candidate->picture;

        if ($request->hasFile('picture')) {
            // Delete previous picture if it exists
            Storage::delete('public/' . $candidate->picture);

            $request_file = $request->file('picture');
            $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
            $path = $request_file->storeAs('candidate', $filename, 'public');
        }

        $candidate->update([
            'first_name'        => $request->first_name,
            'last_name'         => $request->last_name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'alternative_phone' => $request->alternative_phone,
            'ssn'               => $request->ssn,
            'present_address'   => $request->present_address,
            'permanent_address' => $request->permanent_address,
            'country_id'        => $request->country_id,
            'city'              => $request->city,
            'zip'               => $request->zip,
            'picture'           => $path,
        ]);

        // Insert new education records
        $educationInfo = $request->input('degree');
        foreach ($educationInfo as $key => $degree) {
            if(!empty($degree)){
                $educationRecord = [
                    'candidate_id'  => $candidate->id,
                    'degree'        => $degree,
                    'university'    => $request->input('university')[$key],
                    'cgpa'          => $request->input('cgpa')[$key],
                    'comments'      => $request->input('comments')[$key],
                ];

                CandidateEducation::create($educationRecord);
            }
        }

        // Insert new work experience records
        $workExpInfo = $request->input('company_name');
        foreach ($workExpInfo as $key => $company_name) {
            if(!empty($company_name)){
                $workExpRecord = [
                    'candidate_id'  => $candidate->id,
                    'company_name'  => $company_name,
                    'working_period'=> $request->input('working_period')[$key],
                    'duties'        => $request->input('duties')[$key],
                    'supervisor'    => $request->input('supervisor')[$key],
                ];

                CandidateWorkExperience::create($workExpRecord);
            }
        }

        return redirect()->route('candidate.index')->with('success', localize('candidate_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(CandidateInformation $candidate)
    {
        $candidate->educations()->delete();
        $candidate->workExperiences()->delete();

        if ($candidate->picture) {
            Storage::delete('public/' . $candidate->picture);
        }

        $candidate->delete();
        return response()->json(['success' => 'success']);
    }
}

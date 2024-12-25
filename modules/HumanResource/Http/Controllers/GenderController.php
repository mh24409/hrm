<?php

namespace Modules\HumanResource\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Modules\HumanResource\Entities\Gender;
use Illuminate\Contracts\Support\Renderable;

class GenderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('humanresource::gender.index',[
            'genders' => Gender::paginate(15),
        ]);
    }    

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'gender_name' => 'required',
        ]);

        $gender = new Gender();
        $gender->create($request->all());
        Toastr::success('Gender Created successfully :)','Success');
        return redirect()->route('genders.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $uuid)
    {        
        $request->validate([
            'gender_name' => 'required',
        ]);
        
        $gender = Gender::where('uuid', $uuid)->firstOrFail();
        $gender->update($request->all());
        Toastr::success('Gender Updated successfully :)','Success');
        return redirect()->route('genders.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $uuid
     * @return Renderable
     */
    public function destroy($uuid)
    {        
        Gender::where('uuid', $uuid)->delete();
        Toastr::success('Gender Deleted successfully :)','Success');
        return response()->json(['success' => 'success']);
    }
}

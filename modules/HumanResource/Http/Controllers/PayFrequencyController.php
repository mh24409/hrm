<?php

namespace Modules\HumanResource\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Modules\HumanResource\Entities\PayFrequency;

class PayFrequencyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('humanresource::pay-frequency.index', [
            'countries' => PayFrequency::get()
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
            'frequency_name' => 'required',
        ]);
        PayFrequency::create($request->all());
        Toastr::success('Pay Frequency added successfully :)','Success');
        return redirect()->route('pay-frequencies.index');  
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
            'frequency_name' => 'required',
        ]);

        $pay_frequency = PayFrequency::where('uuid', $uuid)->firstOrFail();
        $pay_frequency->update($request->all());
        Toastr::success('Pay Frequency Updated successfully :)','Success');
        return redirect()->route('pay-frequencies.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($uuid)
    {
        PayFrequency::where('uuid', $uuid)->delete();
        Toastr::success('Pay Frequency Deleted successfully :)','Success');
        return response()->json(['success' => 'success']);
    }
}

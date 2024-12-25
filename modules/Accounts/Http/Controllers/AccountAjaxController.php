<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccCoa;
use Modules\Accounts\Entities\AccSubcode;

class AccountAjaxController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function getCoaFromSubtype($subtype)
    {
         $accDropdown = AccCoa::where('head_level',4)->where('is_active',1)->where('subtype_id',$subtype)->where('is_subtype',1)->get();
        return response()->json([
            'coaDropDown'=>$accDropdown,
        ]);
    }

    /**
     * Get the specified resource.
     * @param int $subtypeid
     * @return Renderable
     */
    public function getsubcode($subtypeid)
    {
         $subcodeDropdown = AccSubcode::where('acc_subtype_id',$subtypeid)->where('status',1)->get();
        return response()->json([
            'subcode'=>$subcodeDropdown,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('accounts::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('accounts::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('accounts::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}

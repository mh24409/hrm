<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Modules\Accounts\Entities\AccCoa;
use Illuminate\Contracts\Support\Renderable;
use Modules\Accounts\Entities\AccPredefineAccount;

class AccPredefineAccountController extends Controller
{
    // Apply middleware for permissions based on actions
    public function __construct()
    {
        $this->middleware('permission:read_predefine_accounts')->only('index');
        $this->middleware('permission:create_predefine_accounts')->only('store');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $fourthLevelcoas = AccCoa::where('head_level', 4)->where('is_active', true)->get();
        $thirdLevelcoas  = AccCoa::where('head_level', 3)->where('is_active', true)->get();

        return view('accounts::predefine-account.index', [
            'fourthLevelcoas' => $fourthLevelcoas,
            'thirdLevelcoas'  => $thirdLevelcoas,
            'predefine_account' => AccPredefineAccount::first()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $predefine_account = AccPredefineAccount::first();
        AccPredefineAccount::updateOrCreate(
            ['id' =>  @$predefine_account->id],
            $request->all()
        );

        Toastr::success('Predefine Account Updated successfully :)', 'Success');
        return redirect()->route('predefine-accounts.index');
    }
}

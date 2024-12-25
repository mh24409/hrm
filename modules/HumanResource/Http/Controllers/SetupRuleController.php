<?php

namespace Modules\HumanResource\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HumanResource\Entities\SetupRule;
use Modules\HumanResource\Http\Requests\SetupRuleRequest;

class SetupRuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_setup_rules')->only(['index']);
        $this->middleware('permission:create_setup_rules', ['only' => ['create', 'store']]);
        $this->middleware('permission:update_setup_rules', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_setup_rules', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('humanresource::setup_rule.index', [
            'setup_rules' => SetupRule::paginate(10),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(SetupRuleRequest $request)
    {

        $data = $request->all();
        $setup_rule = new SetupRule();
        if ($request->type != 'time') {
            if ($request->effect_on == 'on_basic') {
                $data['on_basic'] = 1;
                $data['on_gross'] = 0;

            } else {
                $data['on_gross'] = 1;
                $data['on_basic'] = 0;

            }
        }
        $setup_rule->fill($data);
        $setup_rule->save();

        Toastr::success('Setup Rule added successfully :)', 'Success');
        return redirect()->route('setup_rules.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $uuid
     * @return Renderable
     */
    public function setupRulesUpdate(SetupRuleRequest $request, $id)
    {

        $data = $request->all();
        $setup_rule = SetupRule::where('id', $id)->firstOrFail();
        if ($request->type != 'time') {
            if ($request->effect_on == 'on_basic') {
                $data['on_basic'] = 1;
                $data['on_gross'] = 0;

            } else {
                $data['on_gross'] = 1;
                $data['on_basic'] = 0;

            }
        }
        $setup_rule->fill($data);
        $setup_rule->update();

        Toastr::success('Setup Rule Updated successfully :)', 'Success');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $uuid
     * @return Renderable
     */
    public function destroy($id)
    {
        $setUpRule = SetupRule::findOrFail($id);
        $setUpRule->delete();

        Toastr::success('Setup rule deleted successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }
}

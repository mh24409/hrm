<?php

namespace Modules\HumanResource\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\HumanResource\Entities\TaxCalculation;

class TaxCalculationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $taxes = TaxCalculation::all();
        return view('humanresource::tax.index', compact('taxes'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Redirect
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_amount' => 'required|array',
            'start_amount.*' => 'required|numeric',
            'end_amount' => 'required|array',
            'end_amount.*' => 'required|numeric',
            'tax_percent' => 'required|array',
            'tax_percent.*' => 'required|numeric',
            'add_amount' => 'required|array',
            'add_amount.*' => 'required|numeric',
            'id' => 'required|array',
            'id.*' => 'nullable',
        ]);

        $idsInRequest = $request->id ?? [];

        foreach ($request->start_amount as $key => $value) {
            // update or create tax calculation
            TaxCalculation::updateOrCreate(
                ['id' => $request->id[$key]],
                [
                    'min' => $value,
                    'max' => $request->end_amount[$key],
                    'tax_percent' => $request->tax_percent[$key],
                    'add_amount' => $request->add_amount[$key],
                ]
            );
        }

        TaxCalculation::whereNotIn('id', $idsInRequest)->delete();

        return redirect()->route('tax-setup.index')->with('success', localize('Tax calculation saved successfully.'));
    }
}

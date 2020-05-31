<?php

namespace App\Http\Controllers\Admin;

use App\Calculator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $calc = new Calculator();
        foreach ($request->except("_token") as $k => $value) {
            if ($value) {
                $calc->{$k} = $value;
            }
        }
        $calc->save();
        return back();
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Calculator $calculator
     * @return \Illuminate\Http\Response
     */
    public function show(Calculator $calculator)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Calculator $calculator
     * @return \Illuminate\Http\Response
     */
    public function edit(Calculator $calculator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Calculator $calculator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Calculator $calculator)
    {
        foreach ($request->except("_token","_method") as $k => $value) {
            if ($value) {
                $calculator->{$k} = $value;
            }
        }
        $calculator->save();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Calculator $calculator
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calculator $calculator)
    {
        //
    }
}

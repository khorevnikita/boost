<?php

namespace App\Http\Controllers\Admin;

use App\Calculator;
use App\Http\Controllers\Controller;
use App\Step;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
        if (Gate::denies('update-content')) {
            abort(403);
        }

        $calc = new Calculator();
        foreach ($request->except("_token", 'steps') as $k => $value) {
            if ($value) {
                $calc->{$k} = $value;
            }
        }
        $calc->save();
        $calc->steps()->whereNotIn("id", collect($request->steps)->pluck("id"))->delete();

        if ($request->steps) {
            foreach ($request->steps as $step) {
                if (isset($step['id'])) {
                    $s = Step::find($step['id']);
                } else {
                    $s = new Step();
                    $s->calculator_id = $calc->id;
                }
                $s->title = $step['title'];
                $s->price = $step['price'];
                $s->save();
            }
        }
        return response([
            'status' => "success"
        ]);
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
        foreach ($request->except("_token", "_method", 'steps') as $k => $value) {
            if (1) {
                $calculator->{$k} = $value;
            }
        }
        $calculator->save();
        $calculator->steps()->whereNotIn("id", collect($request->steps)->pluck("id"))->delete();
        if ($request->steps) {
            foreach ($request->steps as $step) {
                if (isset($step['id'])) {
                    $s = Step::find($step['id']);
                } else {
                    $s = new Step();
                    $s->calculator_id = $calculator->id;
                }
                $s->title = $step['title'];
                $s->price = $step['price'];
                $s->save();
            }
        }
        return response([
            'status' => "success"
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Calculator $calculator
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calculator $calculator)
    {
        $calculator->delete();
        return back();
    }
}

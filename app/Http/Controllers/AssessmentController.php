<?php

namespace App\Http\Controllers;

use App\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
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
        $request->validate([
            'value' => ['required', "integer", "min:1", "max:5"],
            'product_id' => ['required', "integer", "exists:products,id"]
        ]);

        $user = Auth::user();
        if (!$user) {
            abort(401);
        }
        $assessment = $user->assessments->where("product_id", $request->product_id)->first();
        if (!$assessment) {
            $assessment = new Assessment();
            $assessment->user_id = $user->id;
            $assessment->product_id = $request->product_id;
        }
        $assessment->value = $request->value;
        $assessment->save();

        return response([
            'status' => "success"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Assessment $assessment
     * @return \Illuminate\Http\Response
     */
    public function show(Assessment $assessment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Assessment $assessment
     * @return \Illuminate\Http\Response
     */
    public function edit(Assessment $assessment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Assessment $assessment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Assessment $assessment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Assessment $assessment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assessment $assessment)
    {
        //
    }
}

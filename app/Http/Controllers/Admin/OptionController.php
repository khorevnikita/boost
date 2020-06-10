<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $options = Option::orderBy("id", "desc");
        if ($request->title) {
            $options  = $options->where("title","like","%$request->title%");
        }
        $options = $options->paginate(30);
        return view("admin.options.index", compact('options'));
    }

    public function indexJson(Request $request)
    {
        $options = Option::where("title", "like", "%$request->q%")->take(10)->get();
        return response([
            'status' => "success",
            'options' => $options,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.options.create');
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

        $request->validate([
            "title" => "required|max:255",
            "price" => "required|numeric",
        ]);

        $opt = new Option();
        $opt->title = $request->title;
        $opt->short_description = $request->short_description;
        $opt->price = $request->price;
        $opt->type = $request->type;
        $opt->save();

        return redirect("/admin/options");
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Option $option
     * @return \Illuminate\Http\Response
     */
    public function show(Option $option)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Option $option
     * @return \Illuminate\Http\Response
     */
    public function edit(Option $option)
    {
        return view("admin.options.edit", compact('option'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Option $option
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Option $option)
    {
        if (Gate::denies('update-content')) {
            abort(403);
        }

        $request->validate([
            "title" => "required|max:255",
            "price" => "required|numeric",
        ]);

        $option->title = $request->title;
        $option->short_description = $request->short_description;
        $option->price = $request->price;
        $option->type = $request->type;
        $option->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Option $option
     * @return \Illuminate\Http\Response
     */
    public function destroy(Option $option)
    {
        if (Gate::denies('update-content')) {
            abort(403);
        }

        $option->delete();
        return redirect("/admin/options");
    }
}

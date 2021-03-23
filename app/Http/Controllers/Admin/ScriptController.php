<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Script;
use Illuminate\Http\Request;

class ScriptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        #dd(1);
        $scripts = Script::all();
        return response()->view("admin.scripts.get", compact("scripts"));
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
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $script = new Script();
        $script->place = "header";
        $script->save();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Script $script
     * @return \Illuminate\Http\Response
     */
    public function show(Script $script)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Script $script
     * @return \Illuminate\Http\Response
     */
    public function edit(Script $script)
    {
        //
    }

    /**
     * @param Request $request
     * @param Script $script
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Script $script)
    {
        $request->validate([
            'place' => "required|in:header,footer",
            "value" => "required"
        ]);

        $script->place = $request->place;
        $script->value = $request->value;
        $script->save();
        return back();
    }

    /**
     * @param Script $script
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Script $script)
    {
        $script->delete();
        return back();
    }
}

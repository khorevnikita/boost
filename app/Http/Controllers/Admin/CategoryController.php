<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Game;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
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
    public function create(Request $request)
    {
        $game = Game::find($request->game_id);
        return view("admin.categories.create", compact('game'));
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
            "title" => "required|max:255"
        ]);
        $category = new Category();
        $category->game_id = $request->game_id;
        $category->title = $request->title;
        $category->description = $request->description;
        $category->save();
        return redirect("/admin/games/$category->game_id/edit");
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view("admin.categories.edit", compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        if (Gate::denies('update-content')) {
            abort(403);
        }
        $request->validate([
            "title" => "required|max:255"
        ]);
        $category->title = $request->title;
        $category->description = $request->description;
        $category->save();
        return redirect("/admin/games/$category->game_id/edit");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if (Gate::denies('update-content')) {
            abort(403);
        }

        $game_id = $category->game_id;
        $category->delete();
        return redirect("admin/games/$game_id/edit");
    }
}

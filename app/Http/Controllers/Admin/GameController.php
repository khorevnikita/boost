<?php

namespace App\Http\Controllers\Admin;

use App\Game;
use App\Http\Controllers\Controller;
use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $games = Game::all();
        return view("admin.games.index", compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.games.create");
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

        $slug = Str::slug($request->title, "-");
        $checkUnique = Game::where("rewrite", $slug)->first();

        $game = new Game();
        $game->title = $request->title;
        $game->description = $request->description;
        $game->rewrite = $slug;
        $game->save();

        if ($checkUnique) {
            $slug = $slug . "-" . $game->id;
            $game->rewrite = $slug;
            $game->save();
        }

        return redirect("/admin/games/$game->id/edit");
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Game $game
     * @return \Illuminate\Http\Response
     */
    public function show(Game $game)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Game $game
     * @return \Illuminate\Http\Response
     */
    public function edit(Game $game)
    {

        $categories = $game->categories;
        $game->categories = $categories;
        return view("admin.games.edit", compact("game"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Game $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Game $game)
    {
        if (Gate::denies('update-content')) {
            abort(403);
        }
        $request->validate([
            "title" => "required|max:255"
        ]);

        $checkUnique = Game::where("rewrite", $request->rewrite)->first();
        $game->title = $request->title;
        $game->description = $request->description;
        $game->rewrite = $checkUnique ? $game->rewrite : $request->rewrite;
        $game->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Game $game
     * @return \Illuminate\Http\Response
     */
    public function destroy(Game $game)
    {
        if (Gate::denies('update-content')) {
            abort(403);
        }

        $game->delete();
        return redirect("/admin/games");
    }

    public function banner($game_id, Request $request)
    {
        if (Gate::denies('update-content')) {
            abort(403);
        }

        $game = Game::findOrFail($game_id);
        if ($request->hasFile("file")) {
            if ($game->banner) {
                Storage::disk("public")->delete($game->banner);
            }
            $file = $request->file("file");
            $path = "/games/$game->id/" . $file->getClientOriginalName();
            Storage::disk('public')->put($path, file_get_contents($file), 'public');
            $game->banner = $path;
            $game->save();
        }
        return response([
            'status' => "success"
        ]);
    }
    public function buttonIcon($game_id, Request $request)
    {
        if (Gate::denies('update-content')) {
            abort(403);
        }

        $game = Game::findOrFail($game_id);
        if ($request->hasFile("file")) {
            if ($game->banner) {
                Storage::disk("public")->delete($game->banner);
            }
            $file = $request->file("file");
            $path = "/games/$game->id/" . $file->getClientOriginalName();
            Storage::disk('public')->put($path, file_get_contents($file), 'public');
            $game->button_icon = $path;
            $game->save();
        }
        return response([
            'status' => "success"
        ]);
    }

}

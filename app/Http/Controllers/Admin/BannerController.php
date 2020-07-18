<?php

namespace App\Http\Controllers\Admin;

use App\Banner;
use App\Game;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::all();
        return view("admin.banners.index", compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $games = Game::all();
        return view("admin.banners.create", compact('games'));
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
            // "background" => "required|file",
            "action_url" => "required_with:action_title",
            "action_title" => "required_with:action_url",
        ]);

        $b = new Banner();
        if ($request->game_id) {
            $b->game_id = $request->game_id;
        }
        if ($request->text) {
            $b->text = $request->text;
        }
        if ($request->action_title) {
            $b->action_title = $request->action_title;
        }
        if ($request->action_url) {
            $b->action_url = $request->action_url;
        }
        if ($request->hasFile("background")) {
            $file = $request->file("background");
            $bg_path = "/banners/" . time() . "/bg/." . $file->getClientOriginalExtension();
            Storage::disk('public')->put($bg_path, file_get_contents($file), 'public');
            $b->background = $bg_path;
        }

        if ($request->hasFile("object_image")) {
            $file = $request->file("object_image");
            $image_path = "/banners/" . time() . "/image/." . $file->getClientOriginalExtension();
            Storage::disk('public')->put($image_path, file_get_contents($file), 'public');
            $b->object_image = $image_path;
        }

        $b->save();

        return redirect("admin/banners/$b->id/edit");
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Banner $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Banner $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        $games = Game::all();
        return view('admin.banners.edit', compact('banner', 'games'));
    }

    /**
     * @param Request $request
     * @param Banner $b
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Banner $banner)
    {
        if (Gate::denies('update-content')) {
            abort(403);
        }

        $request->validate([
            "action_url" => "required_with:action_title",
            "action_title" => "required_with:action_url",
        ]);

        if ($request->game_id) {
            $banner->game_id = $request->game_id;
        }
        if ($request->text) {
            $banner->text = $request->text;
        }
        if ($request->action_title) {
            $banner->action_title = $request->action_title;
        }
        if ($request->action_url) {
            $banner->action_url = $request->action_url;
        }
        if ($request->hasFile("background")) {
            Storage::disk('public')->delete($banner->original_background);
            $file = $request->file("background");
            $bg_path = "/banners/" . time() . "/bg/." . $file->getClientOriginalExtension();
            Storage::disk('public')->put($bg_path, file_get_contents($file), 'public');
            $banner->background = $bg_path;
        }

        if ($request->hasFile("object_image")) {
            Storage::disk('public')->delete($banner->original_object_image);
            $file = $request->file("object_image");
            $image_path = "/banners/" . time() . "/image/." . $file->getClientOriginalExtension();
            Storage::disk('public')->put($image_path, file_get_contents($file), 'public');
            $banner->object_image = $image_path;
        }

        $banner->published = $request->published ? 1 : 0;

        $banner->save();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Banner $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        Storage::disk('public')->delete($banner->original_background);
        Storage::disk('public')->delete($banner->original_object_image);
        $banner->delete();
        return redirect("/admin/banners");
    }
}

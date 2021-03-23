<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Promocode;
use foo\bar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PromocodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $promocodes = Promocode::orderBy("id", "desc")->withCount("orders");
        if ($request->id) {
            $promocodes = $promocodes->where("id", $request->id);
        }
        if ($request->title) {
            $promocodes = $promocodes->where("title", "like", "%$request->title%");
        }
        if ($request->code) {
            $promocodes = $promocodes->where("code", "like", "%$request->code%");
        }
        if ($request->value) {
            $promocodes = $promocodes->where("value", "=", $request->value);
        }
        if ($request->currency) {
            $promocodes = $promocodes->where("currency", "=", "$request->currency");
        }
        if ($request->end_at) {
            $promocodes = $promocodes->where("end_at", "like", "%$request->end_at%");
        }

        $promocodes = $promocodes->paginate(30);
        return response()->view("admin.promocodes.index", compact("promocodes"));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view("admin.promocodes.create");
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (Gate::denies('update-content')) {
            abort(403);
        }

        $request->validate([
            'title' => "required|max:255",
            'code' => "required|max:255",
            "value" => "required|integer|min:1",
            "currency" => "required|in:usd,eur,%"
        ]);

        $p = new Promocode();
        $p->title = $request->title;
        $p->value = $request->value;
        $p->currency = $request->currency;
        $p->end_at = $request->end_at;
        $p->code = $request->code;
        $p->save();
        return redirect("/admin/promocodes");
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Promocode $promocode
     * @return \Illuminate\Http\Response
     */
    public function show(Promocode $promocode)
    {
        //
    }

    /**
     * @param Promocode $promocode
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Promocode $promocode)
    {
        return view("admin.promocodes.edit", compact("promocode"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Promocode $promocode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Promocode $promocode)
    {
        if (Gate::denies('update-content')) {
            abort(403);
        }
        $request->validate([
            'title' => "required|max:255",
            'code' => "required|max:255",
            "value" => "required|integer|min:1",
            "currency" => "required|in:usd,eur,%"
        ]);

        $promocode->title = $request->title;
        $promocode->value = $request->value;
        $promocode->currency = $request->currency;
        $promocode->end_at = $request->end_at;
        $promocode->code = $request->code;
        $promocode->save();
        return redirect("/admin/promocodes");
    }

    /**
     * @param Promocode $promocode
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Promocode $promocode)
    {
        if (Gate::denies('update-content')) {
            abort(403);
        }

        $promocode->delete();
        return redirect("/admin/promocodes");
    }
}

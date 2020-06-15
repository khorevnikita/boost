<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Game;
use App\Http\Controllers\Controller;
use App\Option;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::orderBy("id", "desc");
        if ($request->id) {
            $products = $products->where("id", $request->id);
        }
        if ($request->title) {
            $products = $products->where("title", "like","%$request->title%");
        }
        if ($request->category) {
            $category = Category::where("title", "like", "%$request->category%")->pluck("id");
            $products = $products->whereIn("category_id", $category);
        }
        if ($request->game) {
            $game = Game::where("title", "like", "%$request->game%")->pluck("id");
            $products = $products->whereHas("category", function ($q) use ($game) {
                $q->whereIn("game_id", $game);
            });
        }
        $products = $products->paginate(30);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::with("game")->get();
        return view("admin.products.create", compact('categories'));
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
            "short_description" => "required",
            "description" => "required",
            "price" => "required|numeric",
        ]);

        $slug = Str::slug($request->title, "-");
        $checkUnique = Product::where("rewrite", $slug)->first();

        $product = new Product();
        $product->title = $request->title;
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->price = $request->price;
        $product->is_hot = $request->is_hot ? 1 : 0;
        $product->is_new = $request->is_new ? 1 : 0;
        $product->rewrite = $slug;
        $product->save();

        if ($checkUnique) {
            $product->rewrite = $slug . "-" . $product->id;
            $product->save();
        }

        return redirect("admin/products");
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $options = Option::all();
        $calculator = $product->calculator()->with("steps")->first();
        $categories = Category::with("game")->get();
        return view("admin.products.edit", compact('product', 'options', 'calculator','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        if (Gate::denies('update-content')) {
            abort(403);
        }

        $request->validate([
            "title" => "required|max:255",
            "rewrite" => "required|max:255",
            "short_description" => "required",
            "description" => "required",
            "price" => "required|numeric",
        ]);

        $checkUnique = Product::where("rewrite", $request->rewrite)->first();

        $product->title = $request->title;
        $product->short_description = $request->short_description;
        $product->description = $request->description;

        $product->price = $request->price;
        $product->is_hot = $request->is_hot ? 1 : 0;
        $product->is_new = $request->is_new ? 1 : 0;

        $product->category_id = $request->category_id;

        $product->rewrite = $checkUnique ? $product->rewrite : $request->rewrite;
        $product->save();

        $product->options()->detach();
        $product->options()->attach(array_filter($request->options));

        $product->crosses()->detach();
        $product->crosses()->attach(array_filter($request->crosses));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if (Gate::denies('update-content')) {
            abort(403);
        }

        $product->delete();
        return redirect("/admin/products");
    }
}

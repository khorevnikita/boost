<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SeoController extends Controller
{
    public function get()
    {
        $seo = DB::table("seo")->first();
        return view("admin.seo", compact('seo'));
    }

    public function save(Request $request)
    {
        $data = [];
        if ($request->title) {
            $data['title'] = $request->title;
        }
        if ($request->description) {
            $data['description'] = $request->description;
        }
        if ($request->keys) {
            $data['keys'] = $request->keys;
        }

        if ($request->hasFile("image")) {
            $file = $request->file("image");
            $path = "/seo/" . $file->getClientOriginalName();
            Storage::disk('public')->put($path, file_get_contents($file), 'public');
            $data['image'] = $path;
        }

        $checkRecord = DB::table("seo")->first();
        if ($checkRecord) {
            DB::table("seo")->where("id", 1)->update($data);
        } else {
            DB::table("seo")->insert($data);
        }
        return back();
    }
}

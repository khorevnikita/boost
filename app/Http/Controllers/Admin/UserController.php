<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::orderBy("id", "desc");
        if ($request->name) {
            $search = $request->name;
            $users = $users->where(function ($q) use ($search) {
                $q->where(DB::raw("CONCAT(`name`,' ',`surname`)"), "like", "%$search%")->orWhere(DB::raw("CONCAT(`surname`,' ',`name`)"), "like", "%$search%");
            });
        }
        if ($request->email) {
            $users = $users->where("email", "LIKE", "%$request->email%");
        }
        if ($request->phone) {
            $users = $users->where("phone", "LIKE", "%$request->phone%");
        }
        if ($request->role) {
            $users = $users->where('role', $request->role);
        }
        $users = $users->paginate(30);
        return view("admin.users.index", compact('users'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view("admin.users.edit", compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->bonus = $request->bonus;
        $user->phone = $request->phone;
        $user->save();

        return redirect("admin/users/$user->id/edit");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}

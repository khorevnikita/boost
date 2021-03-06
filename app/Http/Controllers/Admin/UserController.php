<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\RegisterMail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
        return view("admin.users.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Illuminate\Support\Facades\Gate::denies('update-users')) {
            abort(403);
        }
        $request->validate([
            'email' => "required|email|unique:users,email"
        ]);

        $password = Str::random(8);
        $user = new User();
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->bonus = $request->bonus;
        $user->phone = $request->phone;
        $user->skype = $request->skype;
        $user->password = bcrypt($password);
        $user->confirmation_token = Str::random();
        $user->save();


        # email here about registration
        try {
            Mail::to($user)->send(new RegisterMail($user, $password));
        } catch (\Exception $e){
            Log::info($e->getMessage());
        }
        return redirect("admin/users/$user->id/edit");
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
        if (\Illuminate\Support\Facades\Gate::denies('update-users')) {
            abort(403);
        }
        $request->validate([
            'email' => "required|email"
        ]);
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

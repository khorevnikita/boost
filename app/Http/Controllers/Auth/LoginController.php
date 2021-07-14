<?php

namespace App\Http\Controllers\Auth;

use App\Game;
use App\Http\Controllers\Controller;
use App\Mail\RegisterMail;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $games = Game::all();
        View::share("games", $games);
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(){
        return redirect("/");
    }

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email|exists:users,email",
            "password" => "required"
        ], $request->all());
        if (!Auth::attempt($request->all(['email', 'password']))) {
            return response()->json([
                'message' => "Credentials are wrong",
                "errors" => [
                    "password" => ["Wrong password"]
                ]
            ], 422);
        }
        $user = User::where("email", $request->email)->first();
        Auth::login($user, $request->remember);
        return response()->json([
            'status' => "success"
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider($driver)
    {
        return Socialite::with($driver)->redirect();
    }

    /**
     *
     */
    public function handleProviderCallback($driver)
    {
        $user = Socialite::driver($driver)->user();
        $id = $user->getId();
        $username = $user->getNickname();
        $name = $user->getName();
        $email = $user->getEmail();
        $avatar = $user->getAvatar();

        if (!$email) {
            return redirect("/register")->with('error', 'Social not return e-mail');
        }
        $user = User::where("email", $email)->first();

        if (!$user) {
            $password = Str::random(8);
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);

            # email here about registration
            try {
                Mail::to($user)->send(new RegisterMail($user, $password));
            } catch (\Exception $e){

            }
        }

        Auth::login($user, true);

        return redirect("/home");
    }

}

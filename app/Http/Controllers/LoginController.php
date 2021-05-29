<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Foundation\Auth\User;
//composer require cartalyst/sentinel
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Cartalyst\Sentinel\Users\UserInterface;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{

    function index()
    {
        return view('auth.login');
    }

    function checklogin(Request $request)
    {
        //Session::flush();

        $this->validate($request, [
            'email'      => 'required',
            'password'   => 'required|alphaNum|min:3',
        ]);

        $user_data = array(
            'email'      => $request->get('email'),
            'password'   => $request->get('password')
        );

        // Se der problemas no blocked, limpar a cache:
        // php artisan optimize:clear - limpa tudo memo
        // php artisan cache:clear
        // php artisan view:clear
        $deleted = User::where('email', $request->input('email'))->value('deleted_at');
        $blocked = User::where('email', $request->input('email'))->value('bloqueado');
        if ($deleted != null) {
            return back()->with('error', "Your account has been disabled!");
        }
        if ($blocked == 1) {
            return back()->with('error', "Your account has been blocked! Contact an administrator if you think it's an error.");
        }

        if (Auth::attempt($user_data)) {
            //$user = User::where('email', request()->email)->first();
            //$user->createToken($user->id, 'authToken')->accessToken;
            return redirect('/shop');
        } else {
            return back()->with('error', 'Wrong email or password!');
        }
    }

    function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect('/shop');
    }

    public function forgotPassword()
    {
        return view('auth.forgot');
    }

    public function sendPasswordResetEmail(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user == null) {
            return back()->with('error', 'User does not exists!');
        }

        Mail::send('email.forgot', ['user' => $user], function ($m) use ($user) {
            $m->from('hello@app.com', 'MagicShirts');

            $m->to($user->email, $user->name)->subject('Reset Password');
        });
        return back()->with('success', 'An email has been sent to you!');
    }

    public function resetPassword(Request $request)
    {
        return view('auth.new_password', ['email' => $request->email]);
    }

    public function saveNewPassword(Request $request)
    {
        $input['password'] = $request->input('password');

        $rules = array('password' => 'required|alphaNum|min:3');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return back()->with('error', 'Error resetting password!');
        }

        if ($request->password != $request->password_confirmation) {
            return back()->with('error', "Error! Passwords don't match.");
        }

        $user = User::where('email', $request->email)->first();

        if ($user == null) {
            return view('auth.forgot')->with('error', 'User does not exists!');
        }

        $user->password = bcrypt($request->input('password'));
        $user->save();

        return redirect('/login')->with('success', 'Password updated successfully!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Foundation\Auth\User;

class LoginController extends Controller
{

    function index()
    {
        return view('pages.login');
    }

    function checklogin(Request $request)
    {

        $this->validate($request, [
            'email'      => 'required',
            'password'   => 'required|alphaNum|min:3'
        ]);

        $user_data = array(
            'email'      => $request->get('email'),
            'password'   => $request->get('password')
        );

        // Se der problemas no blocked, limpar a cache:
        // php artisan optimize:clear
        // php artisan cache:clear
        // php artisan view:clear
        $blocked = User::where('email', $request->input('email'))->value('bloqueado');
        if ($blocked == 1) {
            return back()->with('error', 'User is blocked!');
        }

        if (Auth::attempt($user_data)) {
            return redirect('/shop');
        } else {
            return back()->with('error', 'Wrong email or password!');
        }
    }

    function successLogin()
    {
        return view('pages.shop');
    }

    function logout()
    {
        Auth::logout();
        return back();
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use DateTime;
use Illuminate\Support\Str;
use Illuminate\Support\MessageBag;

class RegisterController extends Controller
{

    function index()
    {
        return view('auth.signup');
    }

    function checkregister(Request $request)
    {

        $this->validate($request, [
            'email'      => 'required',
            'password'  => 'required|alphaNum|min:3|confirmed'
        ]);

        $user_data = array(
            'email'      => $request->get('email'),
            'password'  => $request->get('password'),
        );

        if (Auth::validate($user_data)) {
            return true;
        } else {
            return false;
        }
    }

    public function store(Request $request)
    {
        $user = new User();
        $input['email'] = $request->input('email');

        // Must not already exist in the `email` column of `users` table
        $rules = array('email' => 'unique:users,email');

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return back()->with('error', 'User already exists!');
        }

        $resultEmail = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL);
        if ($resultEmail == true) {

            $user->email = $request->input('email');
            $user->name = $request->input('name');
            $user->email_verified_at = new DateTime();
            $user->password = $request->input('password');
            $user->remember_token = null;
            $user->created_at = new DateTime();
            $user->updated_at = new DateTime();
            $user->tipo = "C";
            $user->bloqueado = 0;
            $user->foto_url = null;
            $user->deleted_at = null;

            $this->setRememberToken($user);

            $user->save();

            return redirect('login')->withSuccess('User registered successfully!');

        } else {
            return back()->with('error', 'Invalid email format!');
        }
    }

    public function setRememberToken($token)
    {
        $token->remember_token = Str::random(20);
    }
}

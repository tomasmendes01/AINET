<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Cliente;

use DateTime;

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
        $input['email'] = $request->input('email');

        // Must not already exist in the `email` column of `users` table
        $rules = array('email' => 'unique:users,email');

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return back()->with('error', 'User already exists!');
        }

        $this->validate($request, [
            'name' => 'required|max:255',
            'password' => 'regex:/^\S*$/u|min:3|confirmed',
        ]);

        $resultEmail = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL);
        if ($resultEmail == true) {

            try {
                DB::beginTransaction();

                /* User */
                $user = new User();

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

                /* Cliente */

                $cliente = new Cliente();

                $cliente->id = $user->id;
                $cliente->nif = null;
                $cliente->endereco = null;
                $cliente->tipo_pagamento = null;
                $cliente->ref_pagamento = null;
                $cliente->created_at = new DateTime();
                $cliente->updated_at = new DateTime();
                $cliente->deleted_at = null;

                $cliente->save();

                DB::commit();

                return redirect('login')->withSuccess('User registered successfully!');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', $e->getMessage());
            }
        } else {
            return back()->with('error', 'Invalid email format!');
        }
    }

    public function setRememberToken($token)
    {
        $token->remember_token = Str::random(20);
    }
}

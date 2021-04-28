<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Cliente;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit($id)
    {
        if (Auth::user()->id != $id && Auth::user()->tipo != 'A') {
            return view('error.pagenotfound');
        };

        try {
            $user = User::with('cliente')->where('id', $id)->first(); // Se não encontrar o perfil da pessoa, vai para o pagenotfound
            //dd($user);
        } catch (\Exception $th) {
            return view('error.pagenotfound');
        }

        return view('user.edit')->with('user', $user);
    }

    public function update(Request $request)
    {
        if ($request->block == null) {
            if (Auth::user()->tipo != 'A') {
                //dd($request->id);
                $this->validate(request(), [
                    'email' => 'required|email|unique:users,email,' . $request->id,
                    'password'  => 'required|alphaNum|min:3|confirmed'
                ]);
            } else {
                $this->validate(request(), [
                    'email' => 'email|unique:users,email,' . $request->id,
                ]);
            }

            if (is_null($request->name)) {
                return back()->with('error', 'Error updating user! Invalid name.');
            }
        }

        try {
            DB::beginTransaction();

            /* User */
            $user = User::find($request->id);
            if ($request->block == null) {
                $user->name = request('name');
                $user->email = request('email');
                $user->password = request('password');

                if ($user->tipo == 'C') {
                    /* Cliente */
                    $cliente = Cliente::find($request->id);

                    $cliente->endereco = request('endereco');

                    $cliente->save();
                }
            } else {
                if (request('block') == 'Unblock') {
                    $user->bloqueado = 0;
                } else {
                    $user->bloqueado = 1;
                }
            }

            $user->save();

            DB::commit();

            return back()->with('success', 'User updated succesfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return back()->with('error', 'Error updating user!');
        }

        return back();
    }
}

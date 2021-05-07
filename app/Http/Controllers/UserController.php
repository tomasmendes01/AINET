<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use App\Models\User;
use App\Models\Cliente;
use DateTime;
use Illuminate\Support\Facades\Hash;

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
        if (request('delete_pfp')) {
            $user = User::find($request->id);
            $user->foto_url = null;
            $user->save();
            return back()->with('success', 'Profile picture deleted succesfully!');
        } elseif (request('profile_picture')) {
            $this->validate($request, [
                'profile_picture' => 'nullable|image|max:1024'
            ]);
            //dd($request->file('profile_picture')->getClientOriginalName());
            $bigPath = $request->profile_picture->store('fotos', 'public');
            $path = substr($bigPath, 6);
            //dd($path);
            $user = User::find($request->id);
            $user->foto_url = $path;
            $user->save();
        }
        if ($request->block == null) {
            if (Auth::user()->tipo != 'A') {
                //dd($request->id);
                if ($request->email != null) {
                    $this->validate(request(), [
                        'email' => 'required|email|unique:users,email,' . $request->id
                    ]);
                }
                if ($request->password != null) {
                    $this->validate(request(), [
                        'password'  => 'alphaNum|min:3|confirmed'
                    ]);
                }
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
            $user = User::where('email', $request->email)->first();
            if ($request->block == null) {

                if ($request->name != null) {
                    $user->name = request('name');
                }
                if ($request->email != null) {
                    $user->email = request('email');
                }
                if ($request->password != null) {
                    $user->password = Hash::make(request('password'));
                }

                if ($request->tipo_user != null) {
                    $user->tipo = request('tipo_user');
                }

                if ($user->tipo == 'C') {
                    /* Cliente */
                    $cliente = Cliente::find($request->id);
                    if ($request->endereco != null) {
                        $cliente->endereco = request('endereco');
                    }
                    if ($request->nif != null) {
                        $this->validate(request(), [
                            'nif' => 'integer|digits:9'
                        ]);
                        $cliente->nif = request('nif');
                    }
                    if ($request->tipo_pagamento != null) {
                        $cliente->tipo_pagamento = request('tipo_pagamento');
                    }
                    if ($request->ref_pagamento != null) {
                        $cliente->ref_pagamento = request('ref_pagamento');
                    }

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
            //dd($e->getMessage());
            return back()->with('error', 'Error updating user! ' . $e->getMessage());
        }

        return back();
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $user->deleted_at = new DateTime();
            $user->save();

            // verificar se o user não é Administrador, pq os Admins não têm "cliente"
            if ($user->tipo != 'A') {
                $cliente = Cliente::findOrFail($user->id);
                $cliente->deleted_at = new DateTime();
                $cliente->save();
            }
            DB::commit();
            $users = User::paginate(12);

            return redirect('/users')->with(['success' => 'User ' . $user->name . ' deleted successfully!', 'users' => $users]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error deleting user!');
        }
    }

    public function checkUpdate(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        try {
            if (Hash::check($request->check_password, $user->password)) {
                $this->update($request);
                return redirect()->back()->with('success', 'User updated succesfully!');
            } else {
                return redirect()->back()->with('error', 'Wrong password or missing confirmation!');
            }
        } catch (\Exception $e) {
            //dd($e);
            return redirect()->back()->with('error', 'Error, missing confirmation!');
        }
    }
}

<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
        if (request('profile_picture')) {
            //dd($request->file('profile_picture')->getClientOriginalName());
            $bigPath = $request->profile_picture->store('fotos','public');
            $path = substr($bigPath, 6);
            //dd($path);
            $user = User::find($request->id);
            $user->foto_url = $path;
            $user->save();
        }
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
            //dd($e->getMessage());
            return back()->with('error', 'Error updating user!');
        }

        return back();
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);

            // verificar se o user não é Administrador, pq os Admins não têm "cliente"
            if ($user->tipo != 'A') {
                Cliente::destroy($id);
            }
            User::destroy($id);

            DB::commit();

            $users = User::paginate(12);

            return redirect('/users')->with(['success' => 'User ' . $user->name . ' deleted successfully!', 'users' => $users]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error deleting user!');
        }
    }
}

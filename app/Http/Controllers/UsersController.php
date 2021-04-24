<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{

    function index()
    {

        if (Auth::user()->tipo != 'A') {
            return '<script>window.location = "/";</script>';
        }

        $users = User::paginate(12);
        //dd($users);
        return view('management.users')->with('users', $users); //envia todos os users para a view users.blade.php como 'users'
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function update(User $user)
    {
        $this->validate(request(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        $user->name = request('name');
        $user->email = request('email');
        $user->password = bcrypt(request('password'));

        $user->save();

        return back();
    }

    public function profile($id){
        
        // Se tentar aceder ao perfil de outra pessoa
        if(Auth::user()->id != $id){
            abort(404);
        };

        $user = User::findOrFail($id);
        return view('user.profile')->with(['users',$user]);
    }
}

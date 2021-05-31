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
use App\Models\Encomenda;
use App\Models\TShirt;
use App\Models\Estampa;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {

        if (Auth::user()->tipo != 'A') {
            return redirect('/');
        }

        $users = User::whereNull('deleted_at')->paginate(12);
        //dd($users);
        return view('management.users')->with('users', $users); //envia todos os users para a view users.blade.php como 'users'
    }

    public function profile($id)
    {
        // Se tentar aceder ao perfil de outra pessoa
        if (Auth::user()->id != $id && Auth::user()->tipo != 'A') {
            return view('error.pagenotfound');
        };

        try {
            $user = User::findOrFail($id); // Se não encontrar o perfil da pessoa, vai para o pagenotfound
            $encomendas = Encomenda::with('cliente', 'tshirt')->where('cliente_id', $user->id)->get();
            //dd($encomendas[0]->tshirt[0]->estampa);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return view('error.pagenotfound');
        }

        return view('user.profile')->with(['user' => $user, 'encomendas' => $encomendas]);
    }

    public function search()
    {
        $search_text = $_GET['query'];
        //dd($search_text);
        $users = User::where('name', 'LIKE', '%' . $search_text . '%')
            ->whereNull('deleted_at')
            ->paginate(12);

        return view('management.users')->with('users', $users);
    }
}

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
use Illuminate\Support\Facades\Redirect;

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

            if ($user->tipo == 'A' || $user->tipo == 'F') { // Se o user for administrador, passa logo pra pagina de edit
                return Redirect::route('user.edit.profile', ['id' => $id]);
            }

            /* Ordenar por data */

            if (request()->orderBy == "data_ascendente") {
                $encomendas = Encomenda::with('cliente', 'tshirt')->where('cliente_id', $user->id)->orderBy('data', 'ASC')->paginate(6);
            } elseif (request()->orderBy == "data_descendente") {
                $encomendas = Encomenda::with('cliente', 'tshirt')->where('cliente_id', $user->id)->orderBy('data', 'DESC')->paginate(6);
                return view('user.profile')->with(['user' => $user, 'encomendas' => $encomendas]);
            } else {
                $encomendas = Encomenda::with('cliente', 'tshirt')->where('cliente_id', $user->id)->paginate(6);
            }

            /* Atribuir preço total da encomenda a partir das tshirts */
            foreach ($encomendas as $encomenda) {
                $preco_total_encomenda = 0;
                foreach ($encomenda->tshirt as $tshirt) {
                    $preco_total_encomenda += $tshirt->subtotal;
                }
                $encomenda->setAttribute('preco_total', $preco_total_encomenda);
            }

            /* Ordenar por preço */
            if (request()->orderBy == "low_high") {
                $encomendas = Encomenda::with('cliente', 'tshirt')->where('cliente_id', $user->id)->orderBy('preco_total', 'ASC')->paginate(6);
            } elseif (request()->orderBy == "high_low") {
                $encomendas = Encomenda::with('cliente', 'tshirt')->where('cliente_id', $user->id)->orderBy('preco_total', 'DESC')->paginate(6);
            } else {
                $encomendas = Encomenda::with('cliente', 'tshirt')->where('cliente_id', $user->id)->paginate(6);
            }

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

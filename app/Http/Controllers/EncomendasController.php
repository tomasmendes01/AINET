<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Encomenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EncomendasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        if (Auth::user()->tipo != 'F') {
            return '<script>window.location("/");</script>';
        }

        //dd(request()->id);
        if (request()->id != null) {

            $encomendas = Encomenda::with('user')
                ->where('cliente_id', request()->client_id)
                ->where('id', request()->id)
                ->get();

            if (count($encomendas) == 0) {
                return redirect('pagenotfound');
            };

            return view('management.encomenda', ['encomendas' => $encomendas]);
        }

        $encomendas = Encomenda::with('user')
            ->where('estado', '=', 'pendente')
            ->orWhere('estado', '=', 'paga')
            ->paginate(12);

        return view('management.encomendas')->with(['encomendas' => $encomendas]);
    }
}

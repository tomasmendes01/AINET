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

        $encomendas = Encomenda::with('user')->paginate(12);

        return view('management.encomendas')->with(['encomendas' => $encomendas]);
    }
}

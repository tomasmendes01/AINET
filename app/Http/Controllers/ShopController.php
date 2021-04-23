<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\DB;
use App\Models\Estampa;
use Illuminate\Pagination\Paginator;
use App\Models\Categoria;

class ShopController extends Controller
{

    function index()
    {
        $estampas = Estampa::with('categoria')->paginate(12);
        $categorias = Categoria::whereNull('deleted_at')->get();
        $cores = DB::table('cores')->whereNull('deleted_at')->get();

        return view('shop.items', ['estampas' => $estampas, 'categorias' => $categorias, 'cores' => $cores]);
    }

    function filter_by_category($id)
    {
        $estampas = Estampa::with('categoria')
            ->where('categoria_id', $id)
            ->whereNull('estampas.deleted_at')
            ->paginate(12);
    
        $categorias = Categoria::whereNull('deleted_at')->get();
        $cores = DB::table('cores')->whereNull('deleted_at')->get();

        return view('shop.items', ['estampas' => $estampas, 'categorias' => $categorias, 'cores' => $cores]);
    }

    function filter_by_color($codigo)
    {
        // go fix this already pls
        $estampas = DB::table('estampas')
            ->leftJoin('categorias', 'categorias.id', '=', 'estampas.categoria_id')
            ->where('categorias.id', $codigo)
            ->whereNull('estampas.deleted_at')
            ->select('estampas.*', 'categorias.nome as categoria')
            ->paginate(12);

        $categorias = DB::table('categorias')->whereNull('deleted_at')->get();
        $cores = DB::table('cores')->whereNull('deleted_at')->get();

        return view('shop.items', ['estampas' => $estampas, 'categorias' => $categorias, 'cores' => $cores]);
    }

    function filter_by_category_and_color($id)
    {
        // go fix this already pls
        $estampas = DB::table('estampas')
            ->leftJoin('categorias', 'categorias.id', '=', 'estampas.categoria_id')
            ->where('categorias.id', $id)
            ->whereNull('estampas.deleted_at')
            ->select('estampas.*', 'categorias.nome as categoria')
            ->paginate(12);

        $categorias = DB::table('categorias')->whereNull('deleted_at')->get();
        $cores = DB::table('cores')->whereNull('deleted_at')->get();

        return view('shop.items', ['estampas' => $estampas, 'categorias' => $categorias, 'cores' => $cores]);
    }
}

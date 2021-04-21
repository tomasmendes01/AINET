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

class ShopController extends Controller
{

    function index()
    {
        $estampas = DB::table('estampas')
            ->leftJoin('categorias', 'categorias.id', '=', 'estampas.categoria_id')
            ->select('estampas.*', 'categorias.nome as categoria')
            ->paginate(12);

        $categorias = DB::table('categorias')->whereNull('deleted_at')->get();
        $cores = DB::table('cores')->whereNull('deleted_at')->get();

        return view('pages.items', ['estampas' => $estampas, 'categorias' => $categorias, 'cores' => $cores]);
    }

    function filter_by_category($id)
    {
        $estampas = DB::table('estampas')
            ->leftJoin('categorias', 'categorias.id', '=', 'estampas.categoria_id')
            ->where('categorias.id', $id)
            ->whereNull('estampas.deleted_at')
            ->select('estampas.*', 'categorias.nome as categoria')
            ->paginate(12);

        $categorias = DB::table('categorias')->whereNull('deleted_at')->get();
        $cores = DB::table('cores')->whereNull('deleted_at')->get();

        return view('pages.items', ['estampas' => $estampas, 'categorias' => $categorias, 'cores' => $cores]);
    }

    function filter_by_color($codigo)
    {
        $estampas = DB::table('estampas')
            ->leftJoin('categorias', 'categorias.id', '=', 'estampas.categoria_id')
            ->where('categorias.id', $codigo)
            ->whereNull('estampas.deleted_at')
            ->select('estampas.*', 'categorias.nome as categoria')
            ->paginate(12);

        $categorias = DB::table('categorias')->whereNull('deleted_at')->get();
        $cores = DB::table('cores')->whereNull('deleted_at')->get();

        return view('pages.items', ['estampas' => $estampas, 'categorias' => $categorias, 'cores' => $cores]);
    }

    function filter_by_category_and_color($id)
    {
        $estampas = DB::table('estampas')
            ->leftJoin('categorias', 'categorias.id', '=', 'estampas.categoria_id')
            ->where('categorias.id', $id)
            ->whereNull('estampas.deleted_at')
            ->select('estampas.*', 'categorias.nome as categoria')
            ->paginate(12);

        $categorias = DB::table('categorias')->whereNull('deleted_at')->get();
        $cores = DB::table('cores')->whereNull('deleted_at')->get();

        return view('pages.items', ['estampas' => $estampas, 'categorias' => $categorias, 'cores' => $cores]);
    }
}

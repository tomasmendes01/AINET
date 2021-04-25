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
use Intervention\Image\Facades\Image;

class ShopController extends Controller
{

    public $nome;

    public function mount($nome)
    {
        $this->nome = $nome;
    }

    function index()
    {
        if (request()->categoria) {
            $estampas = Estampa::with('categoria')
                ->whereHas('categoria', function ($query) {
                    $query->where('nome', request()->categoria);
                })
                ->whereNull('estampas.deleted_at')
                ->paginate(12);
        } else {
            $estampas = Estampa::with('categoria')->paginate(12);
        }

        $categorias = Categoria::whereNull('deleted_at')->get();
        $cores = DB::table('cores')->whereNull('deleted_at')->get();

        return view('shop.items', ['estampas' => $estampas, 'categorias' => $categorias, 'cores' => $cores]);
    }

    public function product()
    {
        $product = Estampa::where('nome', request()->nome)
            ->where('id', request()->id)
            ->get();

        if (request()->cor != null) {
            $cor = DB::table('cores')->where('nome', request()->cor)->get();
        } else {
            $cor = DB::table('cores')->where('nome', 'Preto')->get();
        }

        if (file_exists(public_path('/storage/estampas/' . $product[0]->imagem_url))) {
            $img = public_path('/storage/estampas/' . $product[0]->imagem_url);
        } else {
            $img = storage_path('app/estampas_privadas/' . $product[0]->imagem_url);
        }

        $logo = Image::make($img)->fit(250, 450);
        $base = Image::make(public_path('/storage/tshirt_base/' . $cor[0]->codigo . '.jpg'));
        $preview = Image::make($base)->insert($logo, 'bottom-right', 135, 35);
        $preview->encode('png');
        $type = 'png';
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($preview);

        $cores = DB::table('cores')->whereNull('deleted_at')->get();

        return view('shop.product', ['product' => $product, 'image' => $base64, 'cores' => $cores]);
    }
}

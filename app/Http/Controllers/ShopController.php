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
        //dd($product);

        $img = public_path('/storage/estampas/' . $product[0]->imagem_url);

        $height = Image::make($img)->height();
        $width = Image::make($img)->width();
        //dd($height, $width);
        if ($height > 700 && $width > 700) {
            if ($height > 2000 && $width > 2000) {
                $logo = Image::make($img)->resize($width / 13, $height / 13);
            } else {
                $logo = Image::make($img)->resize($width / 3, $height / 3);
            }
        } else {
            if ($height > 700 || $width > 700) {
                $logo = Image::make($img)->resize($width / 4.3, $height / 4.3);
            } else {
                $logo = Image::make($img)->resize($width / 3, $height / 3);
            }
        }

        $preview = Image::make('img\navbar-logo.png')->insert($logo, 'center');
        $preview->encode('jpg');
        $type = 'jpg';
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($preview);

        return view('shop.product', ['product' => $product,'image' => $base64]);
    }
}

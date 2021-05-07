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
use Illuminate\Support\Facades\Session;

class ShopController extends Controller
{

    public $nome;

    public function mount($nome)
    {
        $this->nome = $nome;
    }

    function index()
    {
        if (request()->filter_by_price) {
            if (request()->filter_by_price == 'high_low') {
                $estampas = Estampa::with('categoria', 'high_low_tshirt')
                    ->whereNull('estampas.deleted_at')
                    ->paginate(12);
            } else {
                $estampas = Estampa::with('categoria', 'low_high_tshirt')
                    ->whereNull('estampas.deleted_at')
                    ->paginate(12);
                //dd($estampas);
            }
        } elseif (request()->categoria) {
            $estampas = Estampa::with('categoria', 'tshirt')
                ->whereHas('categoria', function ($query) {
                    $query->where('nome', request()->categoria);
                })
                ->whereNull('estampas.deleted_at')
                ->paginate(12);
        } else {
            $estampas = Estampa::with('categoria', 'tshirt')
                ->whereNull('estampas.deleted_at')
                ->paginate(12);
        }
        //dd($estampas[0]);
        $categorias = Categoria::whereNull('deleted_at')->get();
        $cores = DB::table('cores')->whereNull('deleted_at')->get();

        return view('shop.items', ['estampas' => $estampas, 'categorias' => $categorias, 'cores' => $cores]);
    }

    public function product()
    {

        // vai buscar o produto com o nome que vem no request
        $product = Estampa::where('nome', request()->nome)
            ->where('id', request()->id)
            ->get();

        // se no request não for pedida nenhuma cor, a $cor fica definida como 'Preto'
        if (request()->cor != null) {
            $cor = DB::table('cores')->where('nome', request()->cor)->get();
        } else {
            $cor = DB::table('cores')->where('nome', 'Preto')->get();
        }

        // procura pela estampa na pasta /storage/estampas e se nao existir, vai à pasta app/estampas_privadas
        if (file_exists(public_path('/storage/estampas/' . $product[0]->imagem_url))) {
            $img = public_path('/storage/estampas/' . $product[0]->imagem_url);
        } else {
            $img = storage_path('app/estampas_privadas/' . $product[0]->imagem_url);
        }

        // código pra processar a estampa e juntar com a t-shirt base
        $logo = Image::make($img)->fit(250, 450);
        $base = Image::make(public_path('/storage/tshirt_base/' . $cor[0]->codigo . '.jpg'));
        $preview = Image::make($base)->insert($logo, 'bottom-right', 135, 35);

        $preview->encode('png');
        $type = 'png';
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($preview);

        // query pra ter todas as cores pro dropdown
        $cores = DB::table('cores')->whereNull('deleted_at')->get();

        return view('shop.product', ['product' => $product, 'image' => $base64, 'cores' => $cores]);
    }

    public function search()
    {
        $estampas = Estampa::where(function ($query) {
            $query->where('nome', 'LIKE', '%' . $_GET['query'] . '%')
                ->orWhere('descricao', 'LIKE', '%' . $_GET['query'] . '%');
        })->paginate(12);

        $categorias = Categoria::whereNull('deleted_at')->get();
        $cores = DB::table('cores')->whereNull('deleted_at')->get();

        return view('shop.items', ['estampas' => $estampas, 'categorias' => $categorias, 'cores' => $cores]);
    }

    public function indexCustomStamp()
    {
        /*
        if (request()->cor != null) {
            $cor = DB::table('cores')->where('nome', request()->cor)->get();
        } else {
            $cor = DB::table('cores')->where('nome', 'Preto')->get();
        }
        */
        $cores = DB::table('cores')->whereNull('deleted_at')->get();

        return view('shop.custom', ['cores' => $cores]);
    }

    public function addToCart(Request $request, $id)
    {
        $product = Estampa::findOrFail($id);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;

        if ($oldCart) {
            $oldCart->add($product, $product->id);
            $request->session()->put('cart', $oldCart);
        } else {
            $cart = new CartController($oldCart);
            $cart->add($product, $product->id);
            $request->session()->put('cart', $cart);
        }

        //dd($request->session()->get('cart'));
        return redirect()->back()->with('success', 'Product added to cart!');
    }
}

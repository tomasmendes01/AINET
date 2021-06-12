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
use App\Models\Preco;
use DateTime;
use Illuminate\Support\Facades\Date;
use App\Models\Encomenda;
use Illuminate\Support\Carbon;

class ShopController extends Controller
{

    protected $appends = ['preco'];

    function index()
    {
        if (request()->categoria) {
            $estampas = Estampa::with('categoria', 'autor')
                ->whereHas('categoria', function ($query) {
                    $query->where('nome', request()->categoria);
                })
                ->whereNull('estampas.deleted_at')
                ->paginate(12);
        } else {
            $estampas = Estampa::with('categoria', 'autor')
                ->whereNull('estampas.deleted_at')
                ->paginate(12);
        }
        $preco_un_catalogo = DB::table('precos')->select('preco_un_catalogo')->first()->preco_un_catalogo;
        $preco_un_proprio = DB::table('precos')->select('preco_un_proprio')->first()->preco_un_proprio;

        foreach ($estampas as $estampa) {
            if ($estampa->cliente_id == null) {
                $estampa->setAttribute('preco', $preco_un_catalogo);
            } else {
                $estampa->setAttribute('preco', $preco_un_proprio);
            }
        }

        $categorias = Categoria::whereNull('deleted_at')->get();
        $cores = DB::table('cores')->whereNull('deleted_at')->get();

        return view('shop.items', ['estampas' => $estampas, 'categorias' => $categorias, 'cores' => $cores]);
    }

    public function product()
    {
        // vai buscar o produto com o nome que vem no request
        $product = Estampa::where('nome', request()->nome)
            ->findOrFail(request()->id);

        // se no request não for pedida nenhuma cor, a $cor fica definida como 'Preto'
        if (request()->cor != null) {
            $cor = DB::table('cores')->where('codigo', request()->cor)->first();
            //$cor = DB::table('cores')->where('nome', request()->cor)->first();
        } else {
            $cor = DB::table('cores')->where('nome', 'Preto')->first();
        }

        /*
        // procura pela estampa na pasta /storage/estampas e se nao existir, vai à pasta app/estampas_privadas
        if (file_exists(public_path('/storage/estampas/' . $product->imagem_url))) {
            $img = public_path('/storage/estampas/' . $product->imagem_url);
        } else {
            $img = storage_path('app/estampas_privadas/' . $product->imagem_url);
        }

        // código pra processar a estampa e juntar com a t-shirt base
        
        $logo = Image::make($img);
        $logo->resize(200, 200, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $base = Image::make(public_path('/storage/tshirt_base/' . $cor->codigo . '.jpg'));
        $preview = Image::make($base)->insert($logo, 'bottom-right', 160, 260);

        $preview->encode('png');
        $type = 'png';
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($preview);
        */

        $preco_un_catalogo = DB::table('precos')->select('preco_un_catalogo')->first()->preco_un_catalogo;
        $preco_un_proprio = DB::table('precos')->select('preco_un_proprio')->first()->preco_un_proprio;

        if ($product->cliente_id == null) {
            $product->setAttribute('preco', $preco_un_catalogo);
        } else {
            $product->setAttribute('preco', $preco_un_proprio);
        }
        $product->setAttribute('cor', $cor);
        // query pra ter todas as cores pro dropdown
        $cores = DB::table('cores')->whereNull('deleted_at')->get();
        //dd($product);
        return view('shop.product', ['prod' => $product, 'cores' => $cores]);
    }

    public function editEstampa($id)
    {
        $estampa = Estampa::findOrFail($id);
        //dd($estampa);
        return view('shop.edit')->with('estampa', $estampa);
    }

    public function deleteEstampa($id)
    {
        $estampa = Estampa::findOrFail($id);
        try {
            DB::beginTransaction();
            $estampa->delete();
            DB::commit();
        } catch (\Exception $e) {
            //throw $th;
            dd($e->getMessage());
            DB::rollBack();
        }

        return redirect('/shop');
    }

    public function search()
    {
        $estampas = Estampa::where(function ($query) {
            $query->where('nome', 'LIKE', '%' . request()['query'] . '%')
                ->orWhere('descricao', 'LIKE', '%' . request()['query'] . '%');
        })->paginate(12);

        $categorias = Categoria::whereNull('deleted_at')->get();
        $cores = DB::table('cores')->whereNull('deleted_at')->get();

        $preco_un_catalogo = DB::table('precos')->select('preco_un_catalogo')->first()->preco_un_catalogo;
        $preco_un_proprio = DB::table('precos')->select('preco_un_proprio')->first()->preco_un_proprio;

        foreach ($estampas as $estampa) {
            if ($estampa->cliente_id == null) {
                $estampa->setAttribute('preco', $preco_un_catalogo);
            } else {
                $estampa->setAttribute('preco', $preco_un_proprio);
            }
        }

        return view('shop.items', ['estampas' => $estampas, 'categorias' => $categorias, 'cores' => $cores]);
    }

    public function indexCustomStamp()
    {
        $categorias = Categoria::all();
        return view('shop.custom', ['categorias' => $categorias]);
    }

    public function createStamp(Request $request)
    {

        try {
            DB::beginTransaction();
            $estampa = new Estampa();

            if (Auth::user()->tipo == 'C') {
                $estampa->cliente_id = Auth::user()->id;
            } else {
                $estampa->cliente_id = null;
            }

            $estampa->categoria_id = null;
            $estampa->nome = $request->stamp_name;
            $estampa->descricao = $request->stamp_description;

            $bigPath = $request->stamp_image->store('estampas_privadas');
            $path = substr($bigPath, 18);
            $estampa->imagem_url = $path;

            $estampa->informacao_extra = null;
            $estampa->created_at = new DateTime();
            $estampa->updated_at = new DateTime();
            $estampa->deleted_at = null;

            $estampa->save();

            DB::commit();

            return redirect('/shop/' . $estampa->nome . '/' . $estampa->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
        return view('shop.custom');
    }

    public function addToCart(Request $request, $id)
    {
        $product = Estampa::findOrFail($id);
        if ($product->cliente_id == null) {
            $preco = DB::table('precos')->select('preco_un_catalogo')->first()->preco_un_catalogo;
            $product->setAttribute('preco', $preco);
            $product->setAttribute('color', $request->color);
            $product->setAttribute('size', $request->size_shirt);
        } else {
            $preco = DB::table('precos')->select('preco_un_proprio')->first()->preco_un_proprio;
            $product->setAttribute('preco', $preco);
            $product->setAttribute('color', $request->color);
            $product->setAttribute('size', $request->size_shirt);
        }

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
        if ($request->ajax()) {
            return back()->renderSections()['content'];
        }

        return back()->with('success', 'Product added to cart!');
    }

    public function saveEstampa($id)
    {
        $estampa = Estampa::findOrFail($id);
        request()->validate([
            'nome' => 'required|max:255',
            'descricao' => 'nullable|max:255',
            'stamp_image' => 'nullable|image|max:1024'
        ]);
        try {
            DB::beginTransaction();
            $estampa->nome = request()->nome;
            $estampa->descricao = request()->descricao;

            if (request()->stamp_image) {
                if ($estampa->cliente_id) {
                    $bigPath = request()->stamp_image->store('estampas_privadas');
                } else {
                    $bigPath = request()->stamp_image->store('estampas', 'public');
                }
                $path = substr($bigPath, 9);
                $estampa->imagem_url = $path;
            }
            $estampa->save();
            DB::commit();
            return back()->with('success', 'Stamp updated successfully!');
        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function getStatistics()
    {
        $encomendas = Encomenda::whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month)->orderBy('data', 'desc')->get();
        return view('admin.statistics')->with('encomendas', $encomendas);
    }
}

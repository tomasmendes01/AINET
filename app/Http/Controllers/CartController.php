<?php

namespace App\Http\Controllers;

use App\Mail\FaturaMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use DateTime;

use App\Models\Estampa;
use App\Models\User;
use App\Models\Encomenda;
use App\Models\Cliente;
use App\Models\TShirt;

use Illuminate\Support\Facades\Date;

use Barryvdh\DomPDF\Facade as PDF;

use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{

    public $items = null;
    public $totalQty = 0;
    public $totalPrice = 0;

    public function index()
    {
        if (request()->session()->get('cart') == null) {
            $cart = new CartController();
            request()->session()->put('cart', $cart);
        }

        return view('user.cart')->with(['cart' => request()->session()->get('cart')]);
    }

    public function add($item, $id)
    {

        $storedItem = ['color' => request()->color, 'size' => request()->size_shirt, 'quantity' => 0, 'price' => 0, 'item' => $item];
        $shirt_pos = $id . request()->size_shirt . request()->color;
        $id = $shirt_pos;
        if ($this->items) {
            if (array_key_exists($id, $this->items) && $item->size == $this->items[$id]['size'] && $item->color == $this->items[$id]['color']) {
                $storedItem = $this->items[$id];
            }
        }

        $storedItem['quantity']++; // vai ao atributo "quantity" que está no array passado pra storage e incrementa o contador
        $quantidade_desconto = DB::table('precos')->value('quantidade_desconto');

        if ($storedItem['quantity'] >= $quantidade_desconto) {

            $preco_un_catalogo = DB::table('precos')->value('preco_un_catalogo');
            $preco_un_proprio = DB::table('precos')->value('preco_un_proprio');
            $preco_un_catalogo_desconto = DB::table('precos')->value('preco_un_catalogo_desconto');
            $preco_un_proprio_desconto = DB::table('precos')->value('preco_un_proprio_desconto');

            if ($item->preco == $preco_un_catalogo) {
                $item->preco = $preco_un_catalogo_desconto;
            } elseif ($item->preco == $preco_un_proprio) {
                $item->preco = $preco_un_proprio_desconto;
            }
        }

        $this->totalPrice += $item->preco;
        $storedItem['price'] += $item->preco;

        $this->items[$shirt_pos] = $storedItem;
        $this->totalQty++;

        request()->session()->put('totalQuantity', $this->totalQty);
        // dd($storedItem['item']->id); = 3

        return view('user.cart');
    }

    public function remove($item)
    {
        if (request()->size_shirt && request()->color) {
            $shirt_pos = $item->id . request()->size_shirt . request()->color;
        } else {
            $shirt_pos = $item->id . $item->size . $item->color;
        }

        if ($this->items) {
            if (array_key_exists($shirt_pos, $this->items)) {
                $storedItem = $this->items[$shirt_pos];
            } else {
                return view('error.pagenotfound');
            }
        } else {
            return view('error.pagenotfound');
        }

        $quantidade_desconto = DB::table('precos')->value('quantidade_desconto');

        if ($storedItem['quantity'] >= $quantidade_desconto) {
            $preco_un_catalogo = DB::table('precos')->value('preco_un_catalogo');
            $preco_un_proprio = DB::table('precos')->value('preco_un_proprio');
            $preco_un_catalogo_desconto = DB::table('precos')->value('preco_un_catalogo_desconto');
            $preco_un_proprio_desconto = DB::table('precos')->value('preco_un_proprio_desconto');
            if ($item->preco == $preco_un_catalogo) {
                $item->preco = $preco_un_catalogo_desconto;
            } elseif ($item->preco == $preco_un_proprio) {
                $item->preco = $preco_un_proprio_desconto;
            }
        }

        // vai ao atributo "quantity" que está no array passado pra storage e incrementa o contador
        $storedItem['quantity']--;
        $this->totalPrice -= $item->preco;

        if ($storedItem['quantity'] == 0) {
            unset($this->items[$shirt_pos]);
        } else {
            $storedItem['price'] -= $item->preco;
            $this->items[$shirt_pos] = $storedItem;
        }

        $this->totalQty--;
        if ($this->totalQty <= 0) {
            $this->totalQty = 0;
            $this->totalPrice = 0;
            request()->session()->put('totalPrice', $this->totalPrice);
        }

        request()->session()->put('totalQuantity', $this->totalQty);

        return view('user.cart');
    }


    public function removeFromCart($item)
    {
        if (request()->quantityToRemove == null) {
            request()->quantityToRemove = 1;
        }
        for ($i = 0; $i < request()->quantityToRemove; $i++) {
            $id = $item;

            if (request()->size_shirt && request()->color) {
                $shirt_pos = $id . request()->size_shirt . request()->color;
                $product = Estampa::findOrFail($id);
            } else {
                $product = Estampa::findOrFail($id['item']->id);
                $shirt_pos = $id['item']->id . $item['size'] . $item['color'];
            }

            if ($product->cliente_id == null) {
                $preco = DB::table('precos')->select('preco_un_catalogo')->first()->preco_un_catalogo;
            } else {
                $preco = DB::table('precos')->select('preco_un_proprio')->first()->preco_un_proprio;
            }
            $product->setAttribute('preco', $preco);
            if (request()->size_shirt && request()->color) {
                $product->setAttribute('size', request()->size_shirt);
                $product->setAttribute('color', request()->color);
            } else {
                $product->setAttribute('size', $item['size']);
                $product->setAttribute('color', $item['color']);
            }

            $oldCart = Session::has('cart') ? Session::get('cart') : null;

            if ($oldCart) {
                $oldCart->remove($product, $shirt_pos);
                request()->session()->put('cart', $oldCart);
            } else {
                //$cart = new CartController($oldCart);
                //$cart->remove($product, $shirt_pos);
                //request()->session()->put('cart', $cart);
                return redirect()->back()->with('error', 'Error fetching cart!');
            }
        }
        //dd($request->session()->get('cart'));
        return redirect()->back()->with('success', 'Product removed from cart!');
    }


    public function clearCart()
    {
        $cart = Session::has('cart') ? Session::get('cart') : null;
        if ($cart == null) {
            return redirect()->back()->with('error', 'Error fetching cart!');
        }

        Session::remove('cart');
        return redirect()->back()->with('success', 'Cart cleared!');
    }

    public function checkout($id)
    {

        if (Auth::user() == null) {
            return redirect()->back()->with('error', 'You must login in order to finish the checkout.');
        } elseif ($id == Auth::user()->id) { // Verifica se a pessoa a fazer o checkout é a mesma pessoa que está logada no site
            try {
                $cliente = Cliente::findOrFail($id);
                $cart = Session::get('cart');
                if ($cart->totalQty == 0) {
                    return redirect()->back()->with('error', 'Error! Cart is empty!');
                }
                DB::beginTransaction();

                /* ENCOMENDAS */
                $encomenda = Encomenda::create([
                    'estado' => "pendente",
                    'cliente_id' => $id,
                    'data' => new DateTime(),
                    'preco_total' => $cart->totalPrice,
                    'nif' => $cliente->nif,
                    'endereco' => $cliente->endereco,
                    'tipo_pagamento' => $cliente->tipo_pagamento,
                    'ref_pagamento' => $cliente->ref_pagamento,
                    'recibo_url' => null,
                    'created_at' => new DateTime(),
                    'updated_at' => new DateTime()
                ]);
                $encomenda->notas = request()->notes;
                $encomenda->save();

                /* TSHIRTS */
                foreach (Session::get('cart')->items as $item) {
                    $colorCode = DB::table('cores')->where('codigo', $item['color'])->first();
                    $tshirt = TShirt::create([
                        'encomenda_id' => $encomenda->id,
                        'estampa_id' => $item['item']->id,
                        'cor_codigo' => $colorCode->codigo,
                        'tamanho' => $item['size'],
                        'quantidade' => $item['quantity'],
                        'preco_un' => $item['price'] / $item['quantity'],
                        'subtotal' => $item['price']
                    ]);
                    $tshirt->save();
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                dd($e->getMessage());
                return redirect()->back()->with('error', 'An error occurred processing your order! Missing parameters (ex: endereco) on your profile');
            }
            /*
            foreach (Session::get('cart')->items as $product) {
                dd($product['color']);
            }
            */
            $this->sendFatura();

            /* Enviar email a avisar que a encomenda está a ser preparada */
            $user = User::findOrFail($encomenda->cliente_id);

            Mail::send('emails.new_order', ['user' => $user], function ($m) use ($user) {
                $m->from('hello@app.com', 'MagicShirts');
                $m->to($user->email, $user->name)->subject('Order Prepared');
            });

            $this->clearCart();

            return redirect()->back()->with('success', 'Your order is being processed...');
        } else {
            return view('error.pagenotfound');
        }
    }

    public function sendFatura()
    {
        $cart = Session::get('cart');
        $user = User::with('cliente')->findOrFail(Auth::user()->id);
        $date = new DateTime();
        $resultDate = $date->format('Y-m-d H:i:s');

        Mail::send('emails.fatura', ['user' => $user, 'cart' => $cart, 'date' => $resultDate], function ($m) use ($user) {
            $m->from('hello@app.com', 'MagicShirts');
            $m->to($user->email, $user->name)->subject('Order Receipt');
        });
    }
}

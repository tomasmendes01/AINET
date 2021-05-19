<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use DateTime;

use App\Models\Estampa;
use App\Models\User;
use App\Models\Encomenda;
use App\Models\Cliente;

class CartController extends Controller
{

    public $items = null;
    public $totalQty = 0;
    public $totalPrice = 0;

    public function index()
    {
        //dd(request()->session()->get('cart'));
        if (request()->session()->get('cart') == null) {
            $cart = new CartController();
            request()->session()->put('cart', $cart);
        }
        return view('user.cart')->with(['cart' => request()->session()->get('cart')]);
    }

    public function add($item, $id)
    {
        $storedItem = ['quantity' => 0, 'price' => $item->preco, 'item' => $item];
        if ($this->items) {
            if (array_key_exists($id, $this->items)) {
                $storedItem = $this->items[$id];
            }
        }

        $storedItem['quantity']++; // vai ao atributo "quantity" que está no array passado pra storage e incrementa o contador
        $storedItem['price'] = $item->preco * $storedItem['quantity'];
        $this->items[$id] = $storedItem;
        $this->totalQty++;
        $this->totalPrice += $item->preco;
        request()->session()->put('totalQuantity', $this->totalQty);
        return view('user.cart');
    }

    public function remove($item, $id)
    {
        if ($this->items) {
            if (array_key_exists($id, $this->items)) {
                $storedItem = $this->items[$id];
            } else {
                return view('error.pagenotfound');
            }
        } else {
            return view('error.pagenotfound');
        }

        $storedItem['quantity']--; // vai ao atributo "quantity" que está no array passado pra storage e incrementa o contador
        $this->totalPrice -= $item->preco;

        if ($storedItem['quantity'] == 0) {
            unset($this->items[$id]);
        } else {
            $storedItem['price'] = $item->preco * $storedItem['quantity'];
            $this->items[$id] = $storedItem;
        }
        $this->totalQty--;

        request()->session()->put('totalQuantity', $this->totalQty);
        return view('user.cart');
    }

    public function removeFromCart(Request $request, $id)
    {
        $product = Estampa::findOrFail($id);
        if ($product->cliente_id == null) {
            $preco = DB::table('precos')->select('preco_un_catalogo')->first()->preco_un_catalogo;
            $product->setAttribute('preco', $preco);
        } else {
            $preco = DB::table('precos')->select('preco_un_proprio')->first()->preco_un_proprio;
            $product->setAttribute('preco', $preco);
        }

        $oldCart = Session::has('cart') ? Session::get('cart') : null;

        if ($oldCart) {
            $oldCart->remove($product, $product->id);
            $request->session()->put('cart', $oldCart);
        } else {
            $cart = new CartController($oldCart);
            $cart->remove($product, $product->id);
            $request->session()->put('cart', $cart);
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
        while ($cart->totalQty > 0) {
            foreach ($cart->items as $item) {
                $this->removeFromCart(request(), $item['item']->id);
            }
        }
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

                $encomenda = new Encomenda();
                $encomenda->estado = "pendente";
                $encomenda->cliente_id = $id;
                $encomenda->data = new DateTime();
                $encomenda->preco_total = $cart->totalPrice;
                $encomenda->notas = request()->notes;
                $encomenda->nif = $cliente->nif;
                $encomenda->endereco = $cliente->endereco;
                $encomenda->tipo_pagamento = $cliente->tipo_pagamento;
                $encomenda->ref_pagamento = $cliente->ref_pagamento;
                $encomenda->recibo_url = null;
                $encomenda->created_at = new DateTime();
                $encomenda->updated_at = new DateTime();

                $encomenda->save();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'An error occurred processing your order!');
            }

            $this->clearCart();
            return redirect()->back()->with('success', 'Your order is being processed...');
        } else {
            return view('error.pagenotfound');
        }
    }
}

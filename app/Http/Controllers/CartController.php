<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Estampa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
}

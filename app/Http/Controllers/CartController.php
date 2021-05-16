<?php

namespace App\Http\Controllers;

class CartController extends Controller
{

    public $items = null;
    public $totalQty = 0;
    public $totalPrice = 0;

    /* não usar esta merda
    public function __construct($oldCart)
    {
        
        if ($oldCart) {
            $this->items = $oldCart->items;
            $this->totalQty = $oldCart->totalQty;
            $this->totalPrice = $oldCart->totalPrice;
        }
        
    }
    */

    public function index()
    {
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
}

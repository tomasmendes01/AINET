<?php

namespace App\Http\Controllers;

class CartController extends Controller
{

    public function index()
    {   
        $counter = 0;
        return view('user.cart')->with('counter',$counter);
    }

    public function add()
    {   
        return view('user.cart');
    }
}
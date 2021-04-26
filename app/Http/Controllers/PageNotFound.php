<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageNotFound extends Controller
{
    //
    function error(){
        return view('error.pagenotfound');
    }
}

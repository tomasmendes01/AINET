@extends('shop')
@section('css')
<link href="/css/product.css" rel="stylesheet" />
@stop

@section('content')
<?php
$prod = $product[0];
?>

@section('content')

<div class="row" style="margin-top:-6%;padding:50px;">
    <div class="column" style="width: auto;">
        <div class="dropdown">
            <button class="dropbtn">⠀⠀⠀Color⠀⠀⠀</button>
            <div class="dropdown-content">
                @foreach($cores as $cor)
                <a href="{{ route('shop.estampa',['id' => $prod->id ,'nome' => $prod->nome ,'cor' => $cor->nome]) }}">{{ $cor->nome }}</a>
                @endforeach
            </div>
        </div>
    </div>
    <div class="column">
        <img src="{{ $image }}" alt="tshirt" style="height: 100%; width: 100%; object-fit: contain">
    </div>
    <div class="column">
        <h2>{{$prod->nome}}</h2>
        <ul>
            <li><strong>Description: </strong>{{ $prod->descricao }}</li>

        </ul>
        <button class="dropbtn">Add to cart</button>
    </div>
</div>


<footer class="footer py-4">
    <div class="container" style="bottom: 0; left: 0; right: 0;">
        <div class="row align-items-center">
            <div class="col-lg-4 text-lg-left">MagicShirts © AINet - Politécnico de Leiria</div>
            <div class="col-lg-4 my-3 my-lg-0">
                <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-twitter"></i></a>
                <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-facebook-f"></i></a>
                <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <div class="col-lg-4 text-lg-right">
                <a class="mr-3" href="#!">Privacy Policy</a>
                <a href="#!">Terms of Use</a>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap core JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Third party plugin JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<!-- Contact form JS-->
<script src="mail/jqBootstrapValidation.js"></script>
<script src="mail/contact_me.js"></script>
<!-- Core theme JS-->
<script src="/js/scripts.js"></script>

@stop
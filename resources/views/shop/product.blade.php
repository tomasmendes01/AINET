@extends('shop')
@section('css')
<link href="/css/product.css" rel="stylesheet" />
@stop

<?php
$prod = $products[0];
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
        <div style="transform:translateY(-100px);margin-bottom:-100px;">
            @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-block" style="text-align:center;margin-top:25%;margin-bottom:-20%;">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
            @endif

            @if (count($errors) > 0)
            <div class="alert alert-danger" style="text-align:center;margin-top:25%;margin-bottom:-20%;">
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(Session::get('success'))
            <div class="alert alert-success" style="text-align:center;margin-top:25%;margin-bottom:-20%;">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{session::get('success')}}</strong>
            </div>
            @endif
        </div>

        <br>


        <h2>{{$prod->nome}}</h2>
        <ul>
            <li><strong>Description: </strong>{{ $prod->descricao }}</li>
            @if($prod->categoria)
            <li><strong>Category: </strong>{{ $prod->categoria->nome }}</li>
            @endif
            <li><strong>Price: </strong>{{ $prod->preco }}€</li>
        </ul>
        <!--<form action="{{ route('cart.add',['id' => $prod->id]) }}" method="get" enctype="multipart/form-data" class="product-form">-->
        <form>
            <input type="button" class="dropbtn" name="teste" value="Add to cart" onClick="addToCart()"></input>
        </form>

        <script>
            function addToCart() {
                $.ajax({
                    type: "get",
                    url: "/cart/add/{{ $prod->id }}",
                });
                alert('Product added to cart!')
            }
        </script>
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

@stop
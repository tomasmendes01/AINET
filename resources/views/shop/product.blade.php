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
            <button class="dropbtn">Color Preview</button>
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
        <form action="{{ route('cart.add',['id' => $prod->id]) }}" method="get" enctype="multipart/form-data" class="product-form">
            <label for="size_shirt">Size:</label>
            <select id="size_shirt" name="size_shirt" size="1">
                <datalist id="size">
                    <option selected="selected" value="XS">Extra Small - XS</option>
                    <option value="S">Small - S</option>
                    <option value="M">Medium - M</option>
                    <option value="L">Large - L</option>
                    <option value="XL">Extra Large - XL</option>
                </datalist>
            </select>

            @if(request()->cor)
            <input type="hidden" name="color" value="{{ request()->cor }}"></input>
            @else
            <input type="hidden" name="color" value="Preto"></input>
            @endif

            <input type="submit" class="dropbtn" value="Add to cart"></input>
        </form>
    </div>
</div>

<!-- Bootstrap core JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Third party plugin JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>

@stop
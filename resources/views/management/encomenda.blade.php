@extends('shop')

@section('css')
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes" />
<meta name="description" content="" />
<meta name="author" content="" />
<title>MagicShirts</title>
<link rel="icon" type="image/x-icon" href="/img/favicon.ico" />
<!-- Font Awesome icons (free version)-->
<script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
<!-- Google fonts-->
<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
<link href="https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
<link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
<!-- Core theme CSS (includes Bootstrap)-->
<link href="/css/shop.css" rel="stylesheet" />
@stop

@section('content')

<style>
    /*encomenda*/

    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 15px;
    }
</style>

<?php
$encomenda = $encomendas[0];
?>

<!-- Portfolio Grid-->
<section class="page-section" id="services" style="margin-top:-6%;">
    <div class="container">
        <div class="text-center" style="margin-top:10%;margin-bottom:-15%;">
            <h2 class="section-heading text-uppercase">Encomenda - {{ $encomenda->id }}</h2>
        </div>
    </div>
</section>

<table style="width:100%;margin-top:12%;">
    <tr>
        <th>Cliente</th>
        <th>Data</th>
        <th>Preço</th>
        <th>Notas</th>
        <th>NIF</th>
        <th>Endereço</th>
        <th>Tipo Pagamento</th>
        <th>Ref Pagamento</th>
        <th>Opções</th>
    </tr>
    <tr>
        <td>{{$encomenda->user->name}}</td>
        <td>{{$encomenda->data}}</td>
        <td>{{$encomenda->preco_total}}€</td>
        <td>{{$encomenda->notas}}</td>
        <td>{{$encomenda->nif}}</td>
        <td>{{$encomenda->endereco}}</td>
        <td>{{$encomenda->tipo_pagamento}}</td>
        <td>{{$encomenda->ref_pagamento}}</td>
        <td>
            <form action="{{ route('encomenda.prepare',['orderID' => $encomenda->id]) }}" method="post">
                @csrf
                <button type="submit" class="btn btn-light">Deliver</button>
            </form>
            <form action="{{ route('encomenda.cancel',['orderID' => $encomenda->id]) }}" method="post">
                @csrf
                <button type="submit" class="btn btn-dark">Cancel</button>
            </form>
        </td>
    </tr>
</table>

<section class="page-section" id="services" style="display:flex;margin-bottom:-5%;margin-left:10%;margin-top:5%;">
    <div class="container">
        <div class="row">
        </div>
    </div>
</section>

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
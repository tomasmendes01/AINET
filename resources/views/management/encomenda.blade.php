@extends('shop')

@section('css')
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes" />
<meta name="description" content="" />
<meta name="author" content="" />
<title>MagicShirts</title>
<link rel="icon" type="image/x-icon" href="/img/favicon.ico"/>
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
            <button type="button" class="btn btn-light">Deliver</button>
            <button type="button" class="btn btn-dark">Cancel</button>
        </td>
    </tr>
</table>

<section class="page-section" id="services" style="display:flex;margin-bottom:-5%;margin-left:10%;margin-top:5%;">
    <div class="container">
        <div class="row">
        </div>
    </div>
</section>

<footer class="footer py-4">
    <div class="container" style="position:absolute; bottom: 0; left: 0; right: 0; margin-bottom:1%">
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
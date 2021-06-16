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
        border-collapse: collapse;
        padding: 15px;
    }

    tr {
        border-bottom: 1px solid #ccc;
    }
</style>

<!-- Portfolio Grid-->
<section class="page-section" id="services" style="margin-top:-6%;">
    <div class="container">
        <div class="text-center" style="margin-top:10%;margin-bottom:-25rem">
            <h2 class="section-heading text-uppercase">Encomenda - {{ $encomenda->id }}</h2>
        </div>
    </div>
</section>

<section class="page-section" id="services">
    <div class="container">
        <div class="row">
            <table style="width:100%;margin-bottom:-10rem">
                <tr>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Price</th>
                    <th>Notes</th>
                    <th>NIF</th>
                    <th>Address</th>
                    <th>Payment Method</th>
                    <th>Payment Reference</th>
                    <th>Actions</th>
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
                            <button type="submit" class="btn btn-light" style="width:100%;margin:auto;margin-bottom:5px;margin-top:-4%">Deliver</button>
                        </form>
                        <div class="portfolio-link" data-toggle="modal" href="#cancelOrderModal">
                            <div class="btn-holder">
                                <input class="btn btn-danger" type="button" style="width:100%;margin:auto;margin-bottom:1%;margin-top:-4%" value="Cancel">
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</section>

<section class="page-section" id="services">
    <div class="container">
        <div class="row">
            <!-- --------------------------------------------- -->
            <table style="width:100%;margin:auto;margin-top:50px;text-align:center">
                <tr>
                    <th>Preview</th>
                    <th>Product</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
                @foreach($encomenda->tshirt as $tshirt)
                <tr>
                    <td>
                        @if($tshirt->estampa != null)
                        <img style="position:absolute;z-index:-1;max-width:250px;max-height:250px;" src="/storage/tshirt_base/{{ $tshirt->cor_codigo }}.jpg">
                        @if($tshirt->estampa->cliente_id)
                        <img src="/estampas_privadas/{{ $tshirt->estampa->imagem_url }}" alt="{{ $tshirt->estampa->imagem_url }}" style="margin-bottom:200px;transform:translateX(75px) translateY(50px);z-index:1;max-width:80px;max-height:auto;" />
                        @else
                        <img src="/storage/estampas/{{ $tshirt->estampa->imagem_url }}" alt="{{ $tshirt->estampa->imagem_url }}" style="margin-bottom:200px;transform:translateX(75px) translateY(50px);z-index:1;max-width:80px;max-height:auto;" />
                        @endif
                        @endif
                    </td>
                    @if($tshirt->estampa != null)
                    <td>{{ $tshirt->estampa->nome }}</td>
                    @else
                    <td><strong>Stamp deleted</strong></td>
                    @endif
                    <td>{{ $tshirt->tamanho }}</td>
                    <td>{{ $tshirt->quantidade }}</td>
                    <td>{{ $tshirt->subtotal }}€</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

    <!-- Cancel Order confirmation -->
    <div class="portfolio-modal modal fade" id="cancelOrderModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-11">
                            <div class="modal-body">
                                <h2>CONFIRMATION</h2>
                                <p class="item">Are you sure you want to cancel this order?</p>
                                <form action="{{ route('encomenda.cancel',['orderID' => $encomenda->id]) }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-primary" style="width:50%;margin:auto;">Cancel Order</button>
                                </form>
                                <button class="btn btn-danger" data-dismiss="modal" type="button" style="width:50%;margin:auto;">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @stop
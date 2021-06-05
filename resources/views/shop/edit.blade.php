@extends('shop')
@section('css')
<link href="/css/product.css" rel="stylesheet" />
@stop

@section('content')
<div class="row" style="margin:auto;margin-left:300px">

    <div class="column">
        @if($estampa->cliente_id)
        <img src="/estampas_privadas/{{ $estampa->imagem_url }}" alt="tshirt" style="height: 100%; width: 100%; object-fit: contain">
        @else
        <img src="/storage/estampas/{{ $estampa->imagem_url }}" alt="tshirt" style="height: 100%; width: 100%; object-fit: contain">
        @endif
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
        <form method="POST" action="{{ route('shop.checkUpdate' , ['id' => $estampa->id]) }}" enctype="multipart/form-data">
            @csrf

            <label for="nome">Name</label>
            <input type="text" id="nome" name="nome" size="50%" value="{{$estampa->nome}}"><br><br>

            <label for="descricao">Description</label>
            <input type="text" id="descricao" name="descricao" size="50%" value="{{$estampa->descricao}}"><br><br>

            <label for="stamp_image">Stamp</label>
            <input type="file" name="stamp_image" size="50%"><br><br>

            <input class="btn btn-primary" type="submit" value="Save" size="100%">
        </form>

        <div data-toggle="modal" href="#logoutModal">
            <a class="btn btn-danger">Delete</a>
        </div>
        <br>
    </div>
</div>

<!-- Logout Modal-->
<div class="portfolio-modal modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-11">
                        <div class="modal-body">
                            <h2>Are you sure you want to delete '{{$estampa->nome}}'?</h2>
                            <form id="logout-form" action="{{ route('estampa.delete' , ['id' => $estampa->id]) }}" method="POST">
                                @csrf
                                <input type="submit" value="Delete" class="btn btn-danger">
                            </form>
                            <button class="btn btn-primary" data-dismiss="modal" type="button">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
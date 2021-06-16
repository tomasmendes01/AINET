@extends('shop')

@section('content')

<!-- Portfolio Grid-->
<section class="page-section" id="services" style="margin-top:-6%;">
    <div class="container">
        <div class="text-center" style="margin-top:10rem;margin-bottom:-7rem;">
            <h2 class="section-heading text-uppercase">List of Deliveries</h2>
            @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-block" style="text-align:center">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
            @endif

            @if (count($errors) > 0)
            <div class="alert alert-danger" style="text-align:center">
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(Session::get('success'))
            <div class="alert alert-success" style="text-align:center">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{session::get('success')}}</strong>
            </div>
            @endif
            <!-- --------------------------------------------- -->
            <ul class="pagination" style="display: inline-block;">
                {{ $encomendas->appends(request()->query())->links("pagination::bootstrap-4") }}
            </ul>
            <br>
            <div style="margin-left:5px;display: inline-block;">
                <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Order by Price
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ route('encomendas',['orderBy' => 'price_low_high']) }}">Low - High</a>
                    <a class="dropdown-item" href="{{ route('encomendas',['orderBy' => 'price_high_low']) }}">High - Low</a>
                </div>
            </div>

            <div style="margin-left:5px;display: inline-block;">
                <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Order by Id
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ route('encomendas',['orderBy' => 'id_ascendente']) }}">Ascendent</a>
                    <a class="dropdown-item" href="{{ route('encomendas',['orderBy' => 'id_descendente']) }}">Descendent</a>
                </div>
            </div>

            <div style="margin-left:5px;display: inline-block;">
                <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Order by Date
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ route('encomendas',['orderBy' => 'data_ascendente']) }}">Ascendent</a>
                    <a class="dropdown-item" href="{{ route('encomendas',['orderBy' => 'data_descendente']) }}">Descendent</a>
                </div>
            </div>
            <!-- --------------------------------------------- -->
        </div>
    </div>

</section>

<section class="page-section bg-light" id="portfolio">
    <div class="container">
        <div class="row">
            <!-- --------------------------------------------- -->
            @if(count($encomendas) == 0)
            <div style="display:inline-block;text-align:center;margin:auto">
                <h2>Something went wrong :(</h2>
                <p>No deliveries were found</p>
            </div>
            @endif
            @foreach($encomendas as $encomenda)
            @if($encomenda->user != null)
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="portfolio-item">
                    <a href="{{ route('encomendas',['id' => $encomenda->id ,'client_id' => $encomenda->cliente_id]) }}">
                        <img class="img-fluid" style="margin:auto" src="" onerror="src='img/navbar-logo.png'" alt="" />
                    </a>
                    <div class="portfolio-caption">
                        <div class="portfolio-caption-subheading text-muted"><strong>{{ $encomenda->id }}</strong></div>
                        <div class="portfolio-caption-subheading text-muted">{{ $encomenda->data }}</div>
                        <div class="portfolio-caption-heading">{{ $encomenda->user->name }}</div>
                        <div class="portfolio-caption-subheading text-muted">{{ $encomenda->preco_total }}€</div>
                        <div class="portfolio-caption-subheading text-muted">{{ $encomenda->notas }}</div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
            <!-- --------------------------------------------- -->
        </div>
    </div>
</section>
<section class="page-section" id="services" style="margin-top:-6%;">
    <div class="container">
        <div class="text-center">
            <!-- --------------------------------------------- -->
            <ul class="pagination" style="display: inline-block;">
                {{ $encomendas->appends(request()->query())->links("pagination::bootstrap-4") }}
            </ul>
            <!-- --------------------------------------------- -->
        </div>
    </div>
</section>


@stop

@section('yellowbutton')
<a class="btn btn-primary btn-xl text-uppercase js-scroll-trigger" href="#services">View Orders</a>
@stop
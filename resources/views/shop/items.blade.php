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

@section('welcome')
<!-- Masthead-->
<header class="masthead">
    <div class="container">

        @if(isset(Auth::user()->email))
        <div class="masthead-subheading">Welcome {{ Auth::user()->name }}!</div>
        @else
        <div class="masthead-subheading">Welcome!</div>
        @endif
        <a class="masthead-heading text-uppercase" href="{{ url('/shop') }}">MagicShirts</a>
        <br>
        <br>
        <br>
        <a class="btn btn-light btn-xl text-uppercase js-scroll-trigger" href="#services">View products</a>
    </div>
</header>
@stop

@section('content')

<!-- Products -->
<section class="page-section" id="services" style="margin-bottom:-5%;margin-top: -3%;">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase">Products</h2>
            <h3 class="section-subheading text-muted" style="margin-bottom:2%;">New stamps every week!</h3>
            <div class="text-center">

                <ul class="pagination" style="display: inline-block;margin-bottom:2%">
                    {{ $estampas->appends(request()->query())->links("pagination::bootstrap-4") }}
                </ul>
                <br>

                <div class="dropdown" style="margin-bottom:-17%;">
                    <form class="form-inline my-2 my-lg-0" method="GET" action="{{ url('/shop/search') }}" style="margin:auto;">
                        <input class="form-control mr-sm-2" type="search" name="query" placeholder="Search products">
                        <button class="btn btn-outline-dark my-2 my-sm-0" type="submit">Search</button>
                    </form>
                </div>
                <br>

                <div class="dropdown" style="margin-bottom:-17%;">
                    <button class="btn btn-dark dropbtn">⠀⠀⠀Categories⠀⠀⠀</button>
                    <div class="dropdown-content">
                        <a href="{{ url('/shop') }}">All Categories</a>
                        @foreach($categorias as $categoria)
                        <a href="{{ route('shop.index',['categoria' => $categoria->nome]) }}">{{ $categoria->nome }}</a>
                        @endforeach
                    </div>
                </div>

                @if(isset(Auth::user()->email))
                <div class="dropdown" style="margin-bottom:-17%;">
                    <button class="btn btn-dark dropbtn">
                        <a href="{{ route('shop.customstamp') }}" style="color:white; text-decoration:none;">Make your own t-shirt!</a>
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Portfolio Grid-->
<section class="page-section bg-light" id="portfolio">
    <div class="container">
        <div class="row">
            <!-- --------------------------------------------- -->
            @foreach($estampas as $estampa)
            <div class="col-lg-3">
                <div class="portfolio-item">
                    <div class="portfolio-caption-subheading" style="color:gold;transform:translateX(220px) scale(1.25);position:absolute;background-color:black;width:auto;z-index:0;">⠀{{ $estampa->preco }}€⠀</div>
                    <a href="{{ route('shop.estampa',['nome' => $estampa->nome, 'id' => $estampa->id]) }}">
                        @if($estampa->cliente_id)
                        <img class="img-fluid" src="/estampas_privadas/{{$estampa->imagem_url}}" alt="{{ $estampa->nome }}" style="margin:auto;max-width:200px;max-height:200px;" />
                        @else
                        <img class="img-fluid" src="/storage/estampas/{{$estampa->imagem_url}}" alt="{{ $estampa->nome }}" style="margin:auto;max-width:300px;max-height:300px;" />
                        @endif
                    </a>
                    <div class="portfolio-caption">

                        <div class="portfolio-caption-heading">{{ $estampa->nome }}</div>
                        @if($estampa->categoria)
                        <div class="portfolio-caption-heading text-muted" style="font-size:small">{{ $estampa->categoria->nome }}</div>
                        @endif
                        @if($estampa->cliente_id)
                        <div class="portfolio-caption-subheading text-muted">Author: {{ $estampa->autor->name }}</div>
                        @endif
                    </div>
                </div>
            </div>
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
                {{ $estampas->appends(request()->query())->links("pagination::bootstrap-4") }}
            </ul>
            <!-- --------------------------------------------- -->
        </div>
    </div>
</section>

<!-- Contact-->
<section class="page-section" id="contact">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase">Contact Us</h2>
            <h3 class="section-subheading text-muted">Feel free to contact us if you're having problems!</h3>
        </div>
        <form id="contactForm" name="sentMessage" novalidate="novalidate">
            <div class="row align-items-stretch mb-5">
                <div class="col-md-6">
                    <div class="form-group">
                        <input class="form-control" id="name" type="text" placeholder="Your Name *" required="required" data-validation-required-message="Please enter your name." />
                        <p class="help-block text-danger"></p>
                    </div>
                    <div class="form-group">
                        <input class="form-control" id="email" type="email" placeholder="Your Email *" required="required" data-validation-required-message="Please enter your email address." />
                        <p class="help-block text-danger"></p>
                    </div>
                    <div class="form-group mb-md-0">
                        <input class="form-control" id="phone" type="tel" placeholder="Your Phone *" required="required" data-validation-required-message="Please enter your phone number." />
                        <p class="help-block text-danger"></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-group-textarea mb-md-0">
                        <textarea class="form-control" id="message" placeholder="Your Message *" required="required" data-validation-required-message="Please enter a message."></textarea>
                        <p class="help-block text-danger"></p>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <div id="success"></div>
                <button class="btn btn-primary btn-xl text-uppercase" id="sendMessageButton" type="submit">Send Message</button>
            </div>
        </form>
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

@section('yellowbutton')
@stop
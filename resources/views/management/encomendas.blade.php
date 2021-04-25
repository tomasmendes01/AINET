@extends('shop')

@section('content')

<!-- Portfolio Grid-->
<section class="page-section" id="services" style="margin-top:-6%;">
    <div class="container">
        <div class="text-center" style="margin-top:10%;margin-bottom:-7%;">
        <h2 class="section-heading text-uppercase">List of Deliveries</h2>
            <!-- --------------------------------------------- -->
            <ul class="pagination" style="display: inline-block;">
                {{ $encomendas->appends(request()->query())->links("pagination::bootstrap-4") }}
            </ul>
            <!-- --------------------------------------------- -->
        </div>
    </div>
</section>
<section class="page-section bg-light" id="portfolio">
    <div class="container">
        <div class="row">
            <!-- --------------------------------------------- -->
            @foreach($encomendas as $encomenda)
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="portfolio-item">
                    <a href="{{ route('encomendas',['id' => $encomenda->id ,'client_id' => $encomenda->cliente_id]) }}">
                        <img class="img-fluid" src="" onerror="src='img/navbar-logo.png'" alt="" />
                    </a>
                    <div class="portfolio-caption">
                        <div class="portfolio-caption-subheading text-muted">{{ $encomenda->id }}</div>
                        <div class="portfolio-caption-heading">{{ $encomenda->user->name }}</div>
                        <div class="portfolio-caption-subheading text-muted">{{ $encomenda->preco_total }}€</div>
                        <div class="portfolio-caption-subheading text-muted">{{ $encomenda->notas }}</div>
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
                {{ $encomendas->appends(request()->query())->links("pagination::bootstrap-4") }}
            </ul>
            <!-- --------------------------------------------- -->
        </div>
    </div>
</section>

<footer class="footer py-4">
    <div class="container" style=" bottom: 0; left: 0; right: 0; margin-bottom:1%">
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

@section('yellowbutton')
<a class="btn btn-primary btn-xl text-uppercase js-scroll-trigger" href="#services">View Orders</a>
@stop
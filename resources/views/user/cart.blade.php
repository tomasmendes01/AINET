@extends('shop')

@section('cart')

<!-- Portfolio Grid-->
<section class="page-section" id="services" style="margin-bottom:-5%;">
    <div class="container">
        <div class="row">
            <!-- --------------------------------------------- -->
            @if($counter == 0)
            <h1 style="margin:auto;margin-top:20%;">Looks like your cart is empty.</h1>
            @else
            @foreach($encomendas as $encomenda)
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="portfolio-item">
                    <a class="portfolio-link" data-toggle="modal" href="#portfolioModal1">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content"><i class="fas fa-plus fa-3x"></i></div>
                        </div>
                        <img class="img-fluid" src="" onerror="src='img/navbar-logo.png'" alt="" />
                    </a>
                    <div class="portfolio-caption">
                        <div class="portfolio-caption-subheading text-muted">{{ $encomenda->id }}</div>
                        <div class="portfolio-caption-heading">{{ $encomenda->name }}</div>
                        <div class="portfolio-caption-subheading text-muted">{{ $encomenda->preco_total }}€</div>
                        <div class="portfolio-caption-subheading text-muted">{{ $encomenda->notas }}</div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
            <!-- --------------------------------------------- -->
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

@section('yellowbutton')
<a class="btn btn-primary btn-xl text-uppercase js-scroll-trigger" href="#services">View Cart</a>
@stop
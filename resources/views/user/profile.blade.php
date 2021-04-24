@extends('shop')

@section('cart')

<!-- Portfolio Grid-->
<section class="page-section" id="services" style="margin-bottom:-5%;">
    <div class="container">
        <div class="row">
            
            <!-- --------------------------------------------- -->
            <img scr="storage/fotos/{{Auth::user()->foto_url}}" alt="profile_picture">
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
<a class="btn btn-primary btn-xl text-uppercase js-scroll-trigger" href="#services">View Profile</a>
@stop
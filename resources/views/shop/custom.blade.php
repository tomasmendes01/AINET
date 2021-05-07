@extends('shop')

@section('css')
<link href="/css/custom_stamp.css" rel="stylesheet" />
@stop

<?php
?>

@section('content')

<div class="row" style="margin-top:-6%;padding:50px;">
    <div class="column" style="width: auto;">
        <div class="dropdown">
            <button class="dropbtn">⠀⠀⠀Color⠀⠀⠀</button>
            <div class="dropdown-content">
                @foreach($cores as $cor)
                @endforeach
            </div>
        </div>
    </div>
    <div class="column">
    </div>
    <div class="column">
        <h2>Custom T-Shirt</h2>
        <ul>
            <li>Upload an image to create your own stamp!</li>
        </ul>
        <button class="dropbtn">Add to cart</button>
    </div>
</div>

<footer class="footer py-4">
    <div class="container" style="position:absolute; bottom: 0; left: 0; right: 0; margin-bottom:1%;">
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
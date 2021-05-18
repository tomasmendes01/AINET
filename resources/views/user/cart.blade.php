@extends('shop')

@section('cart')

<section class="page-section" id="services" style="margin-bottom:-5%;">
    <div class="container">
        <div class="row">
            @if($cart->totalPrice == 0 || $cart->items == null )
            <h1 style="margin:auto;margin-top:20%;">Looks like your cart is empty.</h1>
            @else
        </div>
    </div>
</section>

<!-- Portfolio Grid-->
<section class="page-section" id="services" style="margin-bottom:-5%;margin-top:-17%;">
    <div class="container">
        <div class="row">
            <!-- --------------------------------------------- -->

            <table style="width:100%;margin:auto;margin-top:50px;text-align:center">
                <tr>
                    <th></th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                    <th>Price</th>
                </tr>
                @foreach($cart->items as $product)
                <tr>
                    <td> <img class="img-fluid" src="/storage/estampas/{{ $product['item']->imagem_url }}" onerror="src=`/estampas_privadas/{{ $product['item']->imagem_url }}` " alt="{{ $product['item']->imagem_url }}" style="max-width:250px;max-height:250px;" /></td>
                    <td>{{ $product['item']->nome }}</td>
                    <td>{{ $product['quantity'] }}</td>
                    <td>
                        <form action="{{ route('cart.add',['id' => $product['item']->id]) }}" method="get" enctype="multipart/form-data" class="product-form">
                            <button class="btn btn-primary" style="width:50%">Add</button>
                        </form>
                        <form action="{{ route('cart.remove',['id' => $product['item']->id]) }}" method="get" enctype="multipart/form-data" class="product-form">
                            <button class="btn btn-danger" style="width:50%">Remove</button>
                        </form>
                    </td>
                    <td>{{ $product['price'] }}€</td>
                </tr>
                @endforeach
            </table>

            <!-- --------------------------------------------- -->
        </div>
    </div>
    <h1 style="text-align:right">Total price: {{ $cart->totalPrice }}€</h1>

    <div data-toggle="modal" href="#confirmCheckoutModal">
        <button type="submit" class="btn btn-dark" style="float:right">Checkout
    </div>

    <form action="{{ route('cart.clear') }}" method="get" enctype="multipart/form-data" class="product-form">
        <button type="submit" class="btn btn-light" style="float:right">Clear Cart</button>
    </form>


</section>

<!-- Confirm Checkout Modal-->
<div class="portfolio-modal modal fade" id="confirmCheckoutModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-11">
                        <div class="modal-body">
                            <h2>Confirm order</h2>
                            <table style="width:100%">
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                </tr>
                                @foreach($cart->items as $product)
                                <tr>
                                    <td>{{ $product['item']->nome }}</td>
                                    <td>{{ $product['price'] }}€</td>
                                    <td>{{ $product['quantity'] }}</td>
                                </tr>
                                @endforeach
                            </table>
                            <br>
                            <a class="btn btn-primary" href="#">Confirm</a>
                            <form id="confirm-order-form" action="#" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <button class="btn btn-light" data-dismiss="modal" type="button">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endif

<footer class="footer py-4" style="position:fixed; margin:auto; width: 100%;bottom: 0; left: 0; right: 0; margin-bottom:1%;">
    <div class="container">
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
<a class="btn btn-primary btn-xl text-uppercase js-scroll-trigger" href="#services">View Cart</a>
@stop
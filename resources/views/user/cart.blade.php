@extends('shop')

@section('cart')

<!-- Portfolio Grid-->
<section class="page-section" id="services" style="margin-bottom:-5%;">
    <div class="container">
        <div class="row">
            <!-- --------------------------------------------- -->
            @if($cart->totalPrice == 0)
            <h1 style="margin:auto;margin-top:20%;">Looks like your cart is empty.</h1>
            @else

            <table style="width:100%;margin:auto;margin-top:50px;text-align:center">
                <tr>
                    <th></th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Remove</th>
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
                <h1 style="margin:auto;margin-top:20%;color:greenyellow"></h1>
                @endforeach
            </table>

            <!-- --------------------------------------------- -->
        </div>
    </div>
    <h1 style="text-align:right">Total price: {{ $cart->totalPrice }}€</h1>
    <a class="btn btn-dark" style="float:right">CHECKOUT</a>
    @endif
</section>

<footer class="footer py-4">
    <div class="container" style="bottom: 0; left: 0; right: 0; margin-bottom:1%">
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
@extends('shop')

@section('cart')

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
                    <th>Size</th>
                    <th>Color</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                    <th>Price</th>
                </tr>
                @foreach($cart->items as $product)
                <tr>
                    @if($product['item']->cliente_id)
                    <td> <img class="img-fluid" src="/estampas_privadas/{{ $product['item']->imagem_url }}" alt="{{ $product['item']->imagem_url }}" style="max-width:250px;max-height:250px;" /></td>
                    @else
                    <td> <img class="img-fluid" src="/storage/estampas/{{ $product['item']->imagem_url }}" alt="{{ $product['item']->imagem_url }}" style="max-width:250px;max-height:250px;" /></td>
                    @endif

                    <td>{{ $product['item']->nome }}</td>
                    <td>{{ $product['item']->size }}</td>
                    <td>{{ $product['item']->color }}</td>
                    <td>{{ $product['quantity'] }}</td>
                    <td>
                        <form action="{{ route('cart.add',['id' => $product['item']->id]) }}" method="get" enctype="multipart/form-data" class="product-form">
                            <input type="hidden" name="color" value="{{$product['item']->color}}">
                            <input type="hidden" name="size_shirt" value="{{$product['item']->size}}">
                            <button class="btn btn-primary" style="width:50%">Add</button>
                        </form>
                        <form action="{{ route('cart.remove',['id' => $product['item']->id]) }}" method="get" enctype="multipart/form-data" class="product-form">
                            <input type="hidden" name="color" value="{{$product['item']->color}}">
                            <input type="hidden" name="size_shirt" value="{{$product['item']->size}}">
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

    <div data-toggle="modal" href="#confirmClearCartModal">
        <button type="submit" class="btn btn-light" style="float:right">Clear Cart
    </div>


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
                            <p>Final price: <strong>{{ $cart->totalPrice }}€</strong></p>

                            @if(Auth::user() != null)
                            <form action="{{ route('cart.checkout',['customerID' => Auth::user()->id]) }}" method="GET" enctype="multipart/form-data" id="notesForm">
                                <textarea rows="4" cols="40" name="notes" form="notesForm" style="display:flex;justify-content:center" placeholder="Feel free to add some notes here to help us with your order! :)"></textarea>
                                @csrf
                                <br>
                                <button type="submit" class="btn btn-primary" style="float:none">Confirm</button>
                            </form>
                            @else

                            <a class="btn btn-primary" href="{{ route('login') }}" enctype="multipart/form-data">
                                Login
                            </a>
                            @endif

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

<!-- Confirm Clear Cart Modal-->
<div class="portfolio-modal modal fade" id="confirmClearCartModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-15">
                        <div class="modal-body">
                            <h2>Are you sure you want to clear your cart?</h2>
                            <p>This action will remove every item you have in your cart.</p>
                            <form action="{{ route('cart.clear') }}" method="get" enctype="multipart/form-data" class="product-form">
                                <button type="submit" class="btn btn-primary">Clear Cart</button>
                            </form>
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

<footer class="footer py-4" style="width: 100%; margin-bottom:2%; height:2.5rem; bottom:0; left: 0; right: 0">
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
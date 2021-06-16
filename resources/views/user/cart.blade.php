@extends('shop')

@section('cart')

@if ($message = Session::get('error'))
<div class="alert alert-danger alert-block" style="text-align:center;margin-top:8rem;margin-bottom:-8rem">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $message }}</strong>
</div>
@endif

@if (count($errors) > 0)
<div class="alert alert-danger" style="text-align:center;margin-top:8rem;margin-bottom:-8rem">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(Session::get('success'))
<div class="alert alert-success" style="text-align:center;margin-left:50px;margin-top:8rem;margin-bottom:-8rem">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{session::get('success')}}</strong>
</div>
@endif

<section>
    <div class="row">
        @if($cart->totalPrice == 0 || $cart->items == null )
        <h1 style="margin:auto;margin-top:10rem;">Looks like your cart is empty.</h1>
        @else
    </div>
</section>

<!-- Portfolio Grid-->
<section class="page-section" id="services" style="margin-top:-17rem;">
    <div class="container">
        <div class="row">
            <!-- --------------------------------------------- -->
            <table style="width:100%;margin:auto;margin-top:50px;text-align:center">
                <tr>
                    <th></th>
                    <th>Product</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                    <th>Price</th>
                </tr>

                @foreach($cart->items as $product)

                <tr>
                    <td>
                        <img style="position:absolute;z-index:-1;max-width:250px;max-height:250px;" src="/storage/tshirt_base/{{ $product['item']->color }}.jpg">
                        @if($product['item']->cliente_id)
                        <img src="/estampas_privadas/{{ $product['item']->imagem_url }}" alt="{{ $product['item']->imagem_url }}" style="margin-bottom:200px;transform:translateX(75px) translateY(50px);z-index:1;max-width:80px;max-height:auto;" />
                        @else
                        <img src="/storage/estampas/{{ $product['item']->imagem_url }}" alt="{{ $product['item']->imagem_url }}" style="margin-bottom:200px;transform:translateX(75px) translateY(50px);z-index:1;max-width:80px;max-height:auto;" />
                        @endif
                    </td>
                    <td>{{ $product['item']->nome }}</td>
                    <td>{{ $product['item']->size }}</td>
                    <td>{{ $product['quantity'] }}</td>
                    <td>
                        <form action="{{ route('cart.add',['id' => $product['item']->id]) }}" method="get" enctype="multipart/form-data">
                            <input type="number" name="quantityToAdd" placeholder="1" min="0" max="100">
                            <input type="hidden" name="color" value="{{$product['item']->color}}">
                            <input type="hidden" name="size_shirt" value="{{$product['item']->size}}">
                            <button class="btn btn-primary" style="width:50%">Add</button>
                        </form>
                        <form action="{{ route('cart.remove',['id' => $product['item']->id]) }}" method="get" enctype="multipart/form-data" class="product-form">
                            <input type="number" name="quantityToRemove" placeholder="1" min="1" max="100">
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
    <h1 style="text-align:right;margin-bottom:-220px">Total price: {{ $cart->totalPrice }}€</h1>

    <div style="margin-top:220px;margin-bottom:-420px" data-toggle="modal" href="#confirmCheckoutModal">
        <button type="submit" class="btn btn-dark" style="float:right;margin-bottom:5rem">Checkout
    </div>

    <div style="margin-top:220px;margin-bottom:-420px" data-toggle="modal" href="#confirmClearCartModal">
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
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                </tr>
                                @foreach($cart->items as $product)
                                <tr>
                                    <td>{{ $product['item']->nome }}</td>
                                    <td>
                                        <svg width=" 40" height="40">
                                            <rect width="40" height="40" style="fill:#{{ $product['color'] }}" />
                                        </svg>
                                    </td>
                                    <td>{{ $product['size'] }}</td>
                                    <td>{{ $product['quantity'] }}</td>
                                    <td>{{ $product['price'] }}€</td>
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
@stop

@section('yellowbutton')
<a class="btn btn-primary btn-xl text-uppercase js-scroll-trigger" href="#services">View Cart</a>
@stop
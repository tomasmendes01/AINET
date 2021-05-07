@extends('shop')

@section('css')
<link href="/css/profile.css" rel="stylesheet" />
@stop

@section('cart')

<div class="row">
    <div class="column">

        <form method="POST" action="{{ route('user.update' , ['id' => $user->id]) }}" enctype="multipart/form-data">
            @csrf
            <input class="btn btn-dark" name="delete_pfp" type="submit" style="max-width:69%;margin:auto;margin-bottom:20px;" value="Delete Profile Picture">
        </form>

        @if($user->foto_url)
        <img src="/storage/fotos/{{ $user->foto_url }}" alt="profile_picture" style="height: 100%; width: 100%; object-fit: contain">
        @else
        <img src="/img/default-pfp.png" alt="profile_picture" style="height: 100%; width: 100%; object-fit: contain">
        @endif
    </div>

    <div class="column" style="text-align:center;">

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

        @if(Session::get('success'))
        <div class="alert alert-success" style="text-align:center;margin-top:25%;margin-bottom:-20%;">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{session::get('success')}}</strong>
        </div>
        @endif

        <form method="POST" action="{{ route('user.checkUpdate' , ['id' => $user->id]) }}" enctype="multipart/form-data" style="margin-top:20%">
            @csrf

            <label for="name">Nome:</label>
            <input type="text" id="name" value="{{ $user->name }}" name="name" size="50%"><br>

            <label for="email">Email:</label>
            @if(Auth::user()->tipo == 'A')
            <input type="text" id="email" value="{{ $user->email }}" name="email" size="50%"><br>
            @else
            <input type="text" id="email" value="{{ $user->email }}" name="email" size="50%" required><br>
            @endif

            @if($user->tipo == 'C')
            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" value="{{ $user->cliente->endereco }}" name="endereco" size="50%"><br>
            <label for="nif">⠀NIF:⠀</label>
            <input type="text" id="nif" value="{{ $user->cliente->nif }}" name="nif" size="50%"><br>

            <label for="ref_pagamento">Referência de Pagamento:</label>
            <input type="text" id="ref_pagamento" value="{{ $user->cliente->ref_pagamento }}" name="ref_pagamento" size="50%"><br>
            @endif

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="password" size="50%"><br>

            @if(Auth::user()->tipo != 'A')
            <label for="repeat_password">Repeat Password:</label>
            <input type="password" id="repeat_password" name="password_confirmation" size="50%"><br><br>
            @endif

            <label for="image">Profile picture:</label>
            <input type="file" name="profile_picture" size="50%"><br><br>

            @if($user->tipo == 'C')
            <label for="tipo_pagamento">Tipo de Pagamento:</label>
            <select id="tipo_pagamento" name="tipo_pagamento" size="1">
                <datalist id="tipo_pagamento">
                    @if($user->cliente->tipo_pagamento == null)
                    <option value="MC">MC</option>
                    <option value="VISA">VISA</option>
                    <option value="Paypal">Paypal</option>
                    @else

                    @if($user->cliente->tipo_pagamento == "MC")
                    <option selected="selected" value="MC">MC</option>
                    <option value="VISA">VISA</option>
                    <option value="Paypal">Paypal</option>
                    @elseif($user->cliente->tipo_pagamento == "VISA")
                    <option selected="selected" value="VISA">VISA</option>
                    <option value="MC">MC</option>
                    <option value="Paypal">Paypal</option>
                    @else
                    <option selected="selected" value="Paypal">Paypal</option>
                    <option value="MC">MC</option>
                    <option value="VISA">VISA</option>
                    @endif

                    @endif

                </datalist>
            </select>
            @endif

            @if(Auth::user()->tipo == 'A')
            <label for="tipo_user">Tipo:</label>
            <select id="tipo_user" name="tipo_user" size="1">
                <datalist id="tipo_user">

                    @if($user->tipo == "A")
                    <option selected="selected" value="A">Administrador</option>
                    <option value="F">Funcionário</option>
                    <option value="C">Cliente</option>
                    @elseif($user->tipo == "F")
                    <option selected="selected" value="F">Funcionário</option>
                    <option value="A">Administrador</option>
                    <option value="C">Cliente</option>
                    @else
                    <option selected="selected" value="C">Cliente</option>
                    <option value="A">Administrador</option>
                    <option value="F">Funcionário</option>
                    @endif

                </datalist>
            </select><br><br><br>
            @endif
            <br>
            <br>

            <!--
            <div class="btn-holder">
                <input class="btn btn-dark" type="submit" value="Apply">
            </div>-->

            <div class="portfolio-link" data-toggle="modal" href="#portfolioModal0">
                <div class="btn-holder">
                    <input class="btn btn-danger" type="button" style="width:69%;margin:auto;margin-bottom:1%;margin-top:-4%" value="Apply">
                </div>
            </div>

            <!-- Save confirmation -->
            <div class="portfolio-modal modal fade" id="portfolioModal0" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-11">
                                    <div class="modal-body">
                                        <h2>CONFIRMATION</h2>
                                        <p>Input your password in order to save the changes.</p>
                                        <input type="password" id="check_password" name="check_password" placeholder="Input your password here"><br>
                                        <br>
                                        <input id="save" class="btn btn-success" type="submit" style="width:69%;margin:auto;" value="Save">
                                        <button class="btn btn-danger" data-dismiss="modal" type="button" style="width:69%;margin:auto;">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@if(Auth::user()->tipo == 'A')
<div class="row" style="margin-top:-30%;">
    <div class="column">

        <div class="portfolio-link" data-toggle="modal" href="#portfolioModal1">
            <div class="btn-holder">
                <input class="btn btn-danger" name="block" type="submit" style="width:69%;margin:auto;margin-bottom:1%;margin-top:4%" value="Delete User">
            </div>
        </div>

        <form method="POST" action="{{ route('user.update' , ['id' => $user->id]) }}" style="text-align:center;">
            @csrf

            @if($user->bloqueado == 1)
            <input type="text" id="block" value="Unblock" hidden>
            <div class="btn-holder">
                <input class="btn btn-success" name="block" type="submit" style="width:69%;margin:auto;" value="Unblock">
            </div>
            @else
            <input type="text" id="block" value="Block" hidden>
            <div class="btn-holder">
                <input class="btn btn-danger" name="block" type="submit" style="width:69%;margin:auto;" value="Block">
            </div>
            @endif
        </form>
    </div>
</div>
@endif

<!-- Delete confirmation -->
<div class="portfolio-modal modal fade" id="portfolioModal1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-11">
                        <div class="modal-body">
                            <h2>CONFIRMATION</h2>
                            <p class="item">Are you sure you want to delete the user <strong>{{ $user->name }}</strong>?</p>
                            <form method="POST" action="{{ route('user.delete' , ['id' => $user->id]) }}">
                                @csrf
                                <input class="btn btn-danger" name="block" type="submit" style="width:69%;margin:auto;" value="Delete">
                            </form>
                            <button class="btn btn-primary" data-dismiss="modal" type="button" style="width:69%;margin:auto;">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="footer py-4">
    <div class="container" style="bottom: 0; left: 0; right: 0; margin-top:200px;">
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
<a class="btn btn-primary btn-xl text-uppercase js-scroll-trigger" href="#services">View Profile</a>
@stop
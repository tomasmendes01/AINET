@extends('shop')

@section('css')
<link href="/css/profile.css" rel="stylesheet" />
@stop

@section('cart')

<div class="row">
    <div class="column">
        @if($user->foto_url)
        <img src="/storage/fotos/{{ $user->foto_url }}" alt="profile_picture" style="height: 100%; width: 100%; object-fit: contain">
        @else
        <img src="/img/default-pfp.png" alt="profile_picture" style="height: 100%; width: 100%; object-fit: contain">
        @endif
    </div>

    <div class="column" style="text-align:center;">

        @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block" style="text-align:center;margin-top:10%;">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
        @endif

        @if (count($errors) > 0)
        <div class="alert alert-danger" style="text-align:center;margin-top:10%;">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(Session::get('success'))
        <div class="alert alert-success" style="text-align:center;margin-top:10%;">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{session::get('success')}}</strong>
        </div>
        @endif

        <form method="POST" action="{{ route('user.update' , ['id' => $user->id]) }}" style="text-align:center;">
            @csrf

            <label for="name">Nome:</label>
            <input type="text" id="name" value="{{ $user->name }}" name="name" size="50%"><br><br>

            <label for="email">Email:</label>
            @if(Auth::user()->tipo == 'A')
            <input type="text" id="email" value="{{ $user->email }}" name="email" size="50%"><br><br>
            @else
            <input type="text" id="email" value="{{ $user->email }}" name="email" size="50%" required><br><br>
            @endif

            @if($user->tipo == 'C')
            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" value="{{ $user->cliente->endereco }}" name="endereco" size="50%"><br><br>
            @endif

            <label for="new_password">New Password:</label>
            @if(Auth::user()->tipo == 'A')
            <input type="password" id="new_password" name="password" size="50%"><br><br>
            @else
            <input type="password" id="new_password" name="password" size="50%" required><br><br>
            @endif

            @if(Auth::user()->tipo != 'A')
            <label for="repeat_password">Repeat Password:</label>
            <input type="password" id="repeat_password" name="password_confirmation" size="50%" required><br><br>
            @endif


            <div class="btn-holder">
                <input class="btn btn-dark" type="submit" value="Apply">
            </div>
        </form>
    </div>
</div>

@if(Auth::user()->tipo == 'A')
<div class="row" style="margin-top:-30%;">
    <div class="column">
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
<a class="btn btn-primary btn-xl text-uppercase js-scroll-trigger" href="#services">View Profile</a>
@stop
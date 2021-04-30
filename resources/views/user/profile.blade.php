@extends('shop')

@section('css')
<link href="/css/profile.css" rel="stylesheet" />
@stop

@section('content')

<div class="row" style="margin-top:-4%">
    <div class="column">
        @if($user->foto_url)
        <img src="/storage/fotos/{{ $user->foto_url }}" onerror="src='/img/default-pfp.png'" alt="profile_picture" style="height: 100%; width: 100%; object-fit: contain">
        @else
        <img src="/img/default-pfp.png" alt="profile_picture" style="height: 100%; width: 100%; object-fit: contain">
        @endif
    </div>
    <div class="column">
        <h2>{{ $user->name }}
            <a class="btn btn-light" href="{{ route('user.edit.profile',['id' => $user->id]) }}">Edit Profile</a>
        </h2>
        <ul>
            <li><strong>Email: </strong>{{ $user->email }}</li>

            @if($user->tipo == 'A')
            <li><strong>Tipo: </strong>Administrador</li>
            @elseif($user->tipo == 'F')
            <li><strong>Tipo: </strong>Funcionário</li>
            @else
            <li><strong>Tipo: </strong>Cliente</li>
            @endif

            @if(Auth::user()->tipo == 'A')
            @if($user->bloqueado == 1)
            <li><strong>Bloqueado: </strong>Sim</li>
            @else
            <li><strong>Bloqueado: </strong>Não</li>
            @endif
            @endif

        </ul>



    </div>

</div>

<footer class="footer py-4">
    <div class="container" style="position: absolute; bottom: 0; left: 0; right: 0; margin-bottom:1%">
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
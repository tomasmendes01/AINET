@extends('shop')

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" rel="stylesheet" type="text/css" />
<link href="/css/profile.css" rel="stylesheet" />
@stop

@section('content')
<div class="container">
    <div class="profile-header">
        <div class="profile-img">
            @if($user->foto_url)
            <img src="/storage/fotos/{{ $user->foto_url }}" onerror="src='/img/default-pfp.png'" alt="profile_picture" width="200">
            @else
            <img src="/img/default-pfp.png" alt="profile_picture" width="200">
            @endif

        </div>
        <div class="profile-nav-info">
            <h3 class="user-name">{{ $user->name }}</h3>
            <div class="address">
                @if($user->tipo == 'A')
                <p id="state" class="state">Administrador</p>
                @elseif($user->tipo == 'F')
                <p id="state" class="state">Funcionário</p>
                @else
                <p id="state" class="state">Cliente</p>
                @endif
            </div>
        </div>
    </div>

    <div class="main-bd">
        <div class="left-side">
            <div class="profile-side">
                <div class="user-bio">
                    <h3>User Info</h3>
                    <p class="user-mail"><i class="fa fa-envelope"></i> {{ $user->email }}</p>

                    @if($user->tipo == 'C')
                    <p style="display:inline"><strong>NIF:</strong> </p>
                    <p style="display:inline">{{ $user->cliente->nif }}</p><br>

                    <p style="display:inline"><strong>Endereço:</strong> </p>
                    <p style="display:inline">{{ $user->cliente->endereco }}</p><br>

                    <p style="display:inline"><strong>Tipo de pagamento:</strong> </p>
                    <p style="display:inline">{{ $user->cliente->tipo_pagamento }}</p><br>

                    <p style="display:inline"><strong>Referência de pagamento:</strong> </p>
                    <p style="display:inline">{{ $user->cliente->ref_pagamento }}</p>
                    @endif
                </div>

                <div class="profile-btn">
                    <button class="chatbtn" id="chatBtn" onclick="location.href = '/shop'"><i class="fa fa-comment"></i> Go back to Shop</button>
                </div>

            </div>


        </div>
        <div class="right-side">

            <div class="nav">
                <ul>
                    @if($user->tipo == 'C')
                    <li onclick="location.href = '{{ route('user.profile',['id' => $user->id]) }}'" class="user-setting">Statistics</li>
                    @endif
                    <li class="user-post active">Settings</li>
                </ul>
            </div>

            <!-- Portfolio Grid-->
            <section class="page-section bg-light" id="portfolio" style="margin-left:15px">
                <div class="container" style="margin-top: -80px">

                </div>
            </section>

            <!-- Bootstrap core JS-->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>


            <div class="profile-body" style="margin-left:20px">

            </div>

        </div>
    </div>
</div>
@stop
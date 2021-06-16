@extends('shop')

@section('css')
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
            @if(Session::get('success'))
            <div class="alert alert-success" style="text-align:center;">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{session::get('success')}}</strong>
            </div>
            @endif
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
                    <li class="user-post active">Statistics</li>
                    @endif
                    <li onclick="location.href = `{{ route('user.edit.profile',['id' => $user->id]) }}`" class="user-setting">Settings</li>
                </ul>
            </div>

            <!-- Portfolio Grid-->
            <section class="page-section bg-light" id="portfolio" style="margin-left:15px;width:100%;height:auto">
                <div class="container" style="margin-top: -80px">

                    @if(count($encomendas) > 0)
                    <ul class="pagination">
                        {{ $encomendas->appends(request()->query())->links("pagination::bootstrap-4") }}
                        <div style="margin-left:20px;">
                            <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                Order by Date
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('user.profile',['id' => $user->id, 'orderBy' => 'data_ascendente']) }}">Ascendent</a>
                                <a class="dropdown-item" href="{{ route('user.profile',['id' => $user->id, 'orderBy' => 'data_descendente']) }}">Descendent</a>
                            </div>
                        </div>
                        <div style="margin-left:5px;">
                            <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                Order by Price
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('user.profile',['id' => $user->id, 'orderBy' => 'low_high']) }}">Low - High</a>
                                <a class="dropdown-item" href="{{ route('user.profile',['id' => $user->id, 'orderBy' => 'high_low']) }}">High - Low</a>
                            </div>
                        </div>
                    </ul>
                    @endif
                    <h3>Orders</h3>
                    @if(count($encomendas) == 0)
                    <p>No orders found.</p>
                    @else
                    <div class="row">
                        @foreach($encomendas as $encomenda)
                        <div class="col-lg-4 col-sm-6 mb-4">
                            <!-- Portfolio item 1-->
                            <div class="portfolio-item">
                                <a class="portfolio-link" data-bs-toggle="modal" href="#portfolioModal{{$encomenda->id}}">
                                    <div class="portfolio-hover">
                                        <div class="portfolio-hover-content"><i class="fas fa-plus fa-3x"></i></div>
                                    </div>
                                    <img class="img-fluid" src="/img/plain_white.png" style="margin:auto" />
                                </a>
                                <div class="portfolio-caption">
                                    <div class="portfolio-caption-heading">{{$encomenda->data}}</div>
                                    <div class="portfolio-caption-subheading text-muted">{{$encomenda->preco_total}}€</div>
                                </div>
                            </div>
                        </div>

                        <!-- Portfolio item 1 modal popup-->
                        <div class="portfolio-modal modal fade" id="portfolioModal{{$encomenda->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="container">
                                        <div class="row justify-content-center">
                                            <div class="col-lg-8">
                                                <div class="modal-body">
                                                    <!-- Project details-->
                                                    <h2 class="text-uppercase">{{$encomenda->data}}</h2>
                                                    <table style="transform:translateX(-80px)">
                                                        <tr>
                                                            <th>Estampa⠀</th>
                                                            <th>⠀Tamanho⠀</th>
                                                            <th>⠀Cor⠀</th>
                                                            <th>⠀Quantidade⠀</th>
                                                            <th>⠀Subtotal</th>
                                                        </tr>
                                                        @foreach($encomenda->tshirt as $tshirt)
                                                        <tr>
                                                            @if($tshirt->estampa['cliente_id'])
                                                            <td><img class="img-fluid" src="/estampas_privadas/{{$tshirt->estampa['imagem_url']}}" alt="{{ $tshirt->estampa['nome'] }}" /></td>
                                                            @else
                                                            <td><img class="img-fluid" src="/storage/estampas/{{$tshirt->estampa['imagem_url']}}" alt="{{ $tshirt->estampa['nome'] }}" /></td>
                                                            @endif

                                                            <td>{{$tshirt->tamanho}}</td>
                                                            <td>
                                                                <svg width="40" height="40">
                                                                    <rect width="40" height="40" alt="{{$tshirt->cor_codigo}}" style="fill:#{{$tshirt->cor_codigo}}" />
                                                                </svg>
                                                            </td>
                                                            <td>{{$tshirt->quantidade}}</td>
                                                            <td>{{$tshirt->subtotal}}€</td>
                                                        </tr>
                                                        @endforeach
                                                    </table>
                                                    <button class="btn btn-primary btn-xl text-uppercase" data-bs-dismiss="modal" type="button">
                                                        <i class="fas fa-times me-1"></i>
                                                        Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </section>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    </div>
</div>
@stop
@extends('shop')

@section('css')
<link href="/css/profile.css" rel="stylesheet" />
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    tr {
        border-bottom: 1px solid #ccc;
    }

    th {
        text-align: left;
    }
</style>
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
            <div>
                <h3 class="user-name" style="display:inline-block">{{ $user->name }}</h3>
                @if(Auth::user()->tipo == 'A')
                @if($user->bloqueado == 1)
                <h6 style="background-color:red;color:white;margin:auto;display:inline-block;transform:translateY(-3px)">BLOCKED</h6>
                @else
                <h6 style="background-color:greenyellow;color:white;margin:auto;display:inline-block;transform:translateY(-3px)">UNBLOCKED</h6>
                @endif
                @endif
            </div>
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
                    <li onclick="location.href = `{{ route('user.profile',['id' => $user->id]) }}`" class="user-setting" style="z-index:1">Statistics</li>
                    @endif
                    <li class="user-post active">Settings</li>
                </ul>
            </div>

            @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-block" style="text-align:center;margin-left:50px">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
            @endif

            @if (count($errors) > 0)
            <div class="alert alert-danger" style="text-align:center;margin-left:50px">
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(Session::get('success'))
            <div class="alert alert-success" style="text-align:center;margin-left:50px">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{session::get('success')}}</strong>
            </div>
            @endif
            <div class="profile-body">
                <div class="profile-btn">
                    <form method="POST" action="{{ route('user.update' , ['id' => $user->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <input class="btn btn-dark" name="delete_pfp" type="submit" style="width:300px;left:0;right:50%;position:relative;margin-bottom:5px" value="Delete Profile Picture">
                    </form>
                </div>
                @if(Auth::user()->id != $user->id)
                @if(Auth::user()->tipo == 'A')
                <div class="profile-btn">
                    <div data-toggle="modal" href="#portfolioModal1">
                        <div class="btn-holder">
                            <input class="btn btn-dark" name="delete" style="width:300px;left:0;right:50%;position:relative;margin-bottom:5px" type="submit" value="Delete User">
                        </div>
                    </div>
                </div>
                <div class="profile-btn">
                    <form method="POST" action="{{ route('user.update' , ['id' => $user->id]) }}" style="text-align:center;">
                        @csrf
                        @method("PUT")
                        @if($user->bloqueado == 1)
                        <input type="hidden" id="block" value="Unblock" hidden>
                        <div class="btn-holder">
                            <input class="btn btn-success" name="block" style="width:300px;left:0;right:50%;position:relative;margin-bottom:5px" type="submit" value="Unblock">
                        </div>
                        @else
                        <input type="hidden" id="block" value="Block" hidden>
                        <div class="btn-holder">
                            <input class="btn btn-dark" name="block" style="width:300px;left:0;right:50%;position:relative" type="submit" value="Block">
                        </div>
                        @endif
                    </form>
                </div>
                @endif
                @else

                <form method="POST" action="{{ route('user.checkUpdate' , ['id' => $user->id]) }}" enctype="multipart/form-data" style="margin-top:10px;margin-left:50px">
                    @csrf
                    @method("PUT")
                    <label for="name">Nome:</label>
                    <input type="text" id="name" value="{{ $user->name }}" name="name" size="50%">
                    <br>

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
                @endif
            </div>

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
                                            @method('delete')
                                            <input class="btn btn-danger" name="delete" type="submit" style="width:69%;margin:auto;" value="Delete">
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


            <!-- Bootstrap core JS-->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>

            <div class="profile-body" style="margin-left:20px">

            </div>
        </div>
    </div>
</div>

@stop
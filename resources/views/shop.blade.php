<!DOCTYPE html>
<html>

<head>
    <title>MagicShirts</title>
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="/css/shop.css" rel="stylesheet" />
    @yield('css')

    <style type="text/css">
        .box {
            width: 600px;
            margin: 0 auto;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body id="page-top">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
        <div class="container">
            @if(Auth::user() && Auth::user()->tipo == 'A')
            <div id="mySidenav" class="sidenav" style="z-index:auto;">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                <a href="{{ url('/users') }}"><i class="far fa-address-book"></i> Manage Users</a>
                <a href="#">Manage Products</a>
                <a href="#">Manage Deliveries</a>
            </div>

            <span style="font-size:30px;cursor:pointer;color:white;" onclick="openNav()">&#9776;⠀</span>

            <script>
                function openNav() {
                    document.getElementById("mySidenav").style.width = "250px";
                }

                function closeNav() {
                    document.getElementById("mySidenav").style.width = "0";
                }
            </script>
            @endif
            <a class="navbar-brand js-scroll-trigger" href="{{url('/shop')}}"><img src="img/navbar-logo.png" alt="" /></a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <i class="fas fa-bars ml-1"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav text-uppercase ml-auto">
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="{{ url('/shop') }}"><i class="fa fa-heart"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="{{ url('/cart') }}"><i class="fas fa-shopping-cart"></i> Cart</a>
                    </li>
                    @if(isset(Auth::user()->email))

                    @if(Auth::user()->tipo == 'F')
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="{{ url('/encomendas') }}"><i class="far fa-address-book"></i> Encomendas</a>
                    </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="{{ route('user.profile',['id' => Auth::user()->id]) }}"><i class="fas fa-user"></i> Profile</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="#contact"><i class="fas fa-question-circle"></i> Support</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="{{ url('/logout') }}"><i class="fa fa-bars"></i> Logout</a>
                    </li>
                    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" arialabelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">Select "Logout" below if you are ready to end your
                                    current session.</div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button" datadismiss="modal">Cancel</button>
                                    <a class="btn btn-primary" href="{{route('logout')}}" onclick="event.preventDefault();
 document.getElementById('logout-form').submit();">Logout</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                        @else
                        <li class="nav-item">
                            <a class="nav-link js-scroll-trigger" href="{{ url('/login') }}"><i class="fa fa-bars"></i> Login</a>
                        </li>
                        @endif
                </ul>
            </div>

        </div>
    </nav>
    <div style="width:100%;height:100%;">
        @yield('welcome')
    </div>


    <div style="width:100%;height:100%;">
        @yield('content')
    </div>

    <div class="container">
        @yield('cart')
    </div>

</body>

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

</html>
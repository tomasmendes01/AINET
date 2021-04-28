@extends('shop')

@section('content')

<!-- Portfolio Grid-->
<section class="page-section" id="services" style="margin-top:-6%;">
    <div class="container">
        <div class="text-center" style="margin-top:10%;margin-bottom:-7%;">
            <h2 class="section-heading text-uppercase">List of Users</h2>

            <ul class="pagination" style="display: inline-block;">
                {{ $users->appends(request()->query())->links("pagination::bootstrap-4") }}
            </ul>
            <br>

            <div class="dropdown" style="margin-bottom:-17%;">
                <form class="form-inline my-2 my-lg-0" method="GET" action="{{ route('users.search') }}" style="margin:auto;">
                    <input class="form-control mr-sm-2" type="search" name="query" placeholder="Search users">
                    <button class="btn btn-outline-dark my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>

            <br>
        </div>
    </div>
</section>
<section class="page-section bg-light" id="portfolio" style="margin-top:-2%;">
    <div class="container">
        <div class="row">
            <!-- --------------------------------------------- -->
            @foreach($users as $user)
            <div class="col-lg-3 col-sm-6 mb-4">
                <div class="portfolio-item">
                    @if($user->foto_url)
                    <a href="{{ route('user.profile',['id' => $user->id]) }}">
                        <img class="img-fluid" src="/storage/fotos/{{$user->foto_url}}" style="width:100%;heigth:100%;" alt="" />
                    </a>
                    @else
                    <a href="{{ route('user.profile',['id' => $user->id]) }}">
                        <img class="img-fluid" src="/img/default-pfp.png" alt="" />
                    </a>
                    @endif
                    <div class="portfolio-caption">
                        <div class="portfolio-caption-heading">{{ $user->name }}</div>
                        <div class="portfolio-caption-subheading text-muted">{{ $user->email }}</div>
                    </div>
                </div>
            </div>
            @endforeach
            <!-- --------------------------------------------- -->
        </div>
    </div>
</section>
<section class="page-section" id="services" style="margin-top:-6%;">
    <div class="container">
        <div class="text-center">
            <!-- --------------------------------------------- -->
            <ul class="pagination" style="display: inline-block;">
                {{ $users->appends(request()->query())->links("pagination::bootstrap-4") }}
            </ul>
            <!-- --------------------------------------------- -->
        </div>
    </div>
</section>

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
<a class="btn btn-primary btn-xl text-uppercase js-scroll-trigger" href="#services">View users</a>
@stop
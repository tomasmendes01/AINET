@extends('shop')

@section('css')
<link href="/css/login.css" rel="stylesheet" />
@stop

@section('content')
<br />
<div class="container box" style="margin-top:20%;">
    <h1 style="text-align:center;">Password reset</h1><br />

    @if (Session::has('error'))
    <div class="alert alert-danger alert-block" style="text-align:center;">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ Session::get('error') }}</strong>
    </div>
    @endif

    @if (count($errors) > 0)
    <div class="alert alert-danger" style="text-align:center;">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(Session::has('success'))
    <div class="alert alert-success" style="text-align:center;">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{Session::get('success')}}</strong>
    </div>
    @endif

    <form method="post" action="{{ url('/forgot-password') }}">
        {{ csrf_field() }}

        <input type="email" name="email" id="email" style="background-color:white;color:black;" placeholder="Input your email here">
        <button class="btn btn-dark" type="submit" style="margin:auto;right:0;left:0;position:absolute;transform:translateY(60px)">Submit</button>
    </form>
</div>

<script>
    function togglePassword() {
        var x = document.getElementById("pass");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }

    function goToShop() {
        window.location = "/shop";
    }
</script>

@stop
@extends('shop')

@section('css')
<link href="/css/login.css" rel="stylesheet" />
@stop

@section('content')
    <div class="container box" style="margin-top:30%;">
        <h3 style="text-align:center;">Create a new password</h3><br />

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

        <form method="post" action="{{ url('/reset_password',['email' => $email]) }}">
            {{ csrf_field() }}

            <p style="margin-bottom:0;">Password</p>
            <input type="text" name="password" id="password" style="background-color:white;color:black;">
            <p style="margin-bottom:0;margin-top:1%;">Repeat Password</p>
            <input type="password" name="password_confirmation" id="confirm_password" style="background-color:white;color:black;">

            <button type="submit" style="margin:auto;right:0;left:0;position:absolute;transform:translateY(60px)">Submit</button>
        </form>
    </div>
</body>

</html>

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
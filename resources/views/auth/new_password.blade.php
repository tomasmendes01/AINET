<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>
    <link rel="icon" href="https://i.imgur.com/EAHBQru.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="{{ URL::asset('/css/login.css') }}" rel="stylesheet">
    <style type="text/css">
        .box {
            width: 600px;
            margin: 0 auto;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <br />
    <div class="container box">
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

            <p>Password</p>
            <input type="text" name="password" id="password" style="background-color:white;color:black;">
            <p>Repeat Password</p>
            <input type="password" name="password_confirmation" id="confirm_password" style="background-color:white;color:black;">

            <button type="submit" style="margin:auto;right:0;left:0;position:absolute;transform:translateY(50px)">Submit</button>
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
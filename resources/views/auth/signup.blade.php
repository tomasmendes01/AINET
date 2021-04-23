<!DOCTYPE html>
<html>

<head>
    <title>MagicShirts - Signup</title>
    <link rel="icon" href="https://i.imgur.com/EAHBQru.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="{{ URL::asset('/css/signup.css') }}" rel="stylesheet">
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
        <h3 align="center">Create your account</h3><br />

        @if(isset(Auth::user()->name))
        <script>
            window.location = "/shop";
        </script>
        @endif

        @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block" style="text-align:center;">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
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

        <form method="post" action="{{ url('/store') }}">
            {{ csrf_field() }}

            <div id="wrapper">
                <div id="table">
                    <a id="signinTab" href="{{ url('/login') }}" class="">Sign in</a>
                    <a id="signupTab" class="active">Sign Up</a>
                </div>
                <div id="signup">

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="name" id="username" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="pass">Password</label>
                        <input type="password" id="pass" name="password">
                        <span id="showpwd" class="fa fa-eye-slash" onclick="togglePassword()"></span>
                    </div>

                    <div class="form-group">
                        <label for="pass">Confirm Password</label>
                        <input type="password" id="confpass" name="password_confirmation">
                    </div>

                    <div class="form-group">
                        <label id="checkbox">
                            <input type="checkbox" checked><span class="text">Keep me signed in</span>
                        </label>
                    </div>


                    <div class="form-group">
                        <input id="signinInBtn" name="signup" type="submit" value="Sign up">
                    </div>

                </div>

                <div class="hr"></div>
                <a href="#" id="forget-pass">Forget Password?</a>
            </div>


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
</script>
<h1>Hello {{$user->name}}!</h1>
<p>
    Click the following link to reset your password.
    <br>
    <a href="{{ route('reset_password',['email' => $user->email]) }}">Reset password</a>
</p>
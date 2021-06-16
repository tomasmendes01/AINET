<h1>Hello {{$user->name}}!</h1>
<p>
    Click the following link to reset your password.
    <br>
    <a href="{{ url('/reset-password',['email' => $user->email,'token' => $token]) }}">Reset password</a>
</p>
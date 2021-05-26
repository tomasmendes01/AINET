<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use HasFactory, Notifiable, CanResetPassword;

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {

        //dd(request()->email);
        $user = User::where('email', request()->email)->first();
        if ($user == null) {
            return back()->with('error', 'User does not exists!');
        }

        Mail::send('email.forgot', ['user' => $user, 'token' => $token], function ($m) use ($user) {
            $m->from('hello@app.com', 'MagicShirts');

            $m->to($user->email, $user->name)->subject('Reset Password');
        });
        return back()->with('success', 'An email has been sent to you!');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Add a mutator to ensure hashed passwords
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function checkIfBlocked()
    {
        return $this->attributes['bloqueado'];
    }

    public function getRole()
    {
        return $this->attributes['tipo'];
    }

    public function encomendas()
    {
        return $this->hasMany(Encomenda::class);
    }

    public function admin()
    {
        return $this->attributes['tipo'] == 'A';
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'id', 'id');
    }

    public function estampas()
    {
        return $this->hasMany(Estampa::class, 'cliente_id', 'id');
    }
}

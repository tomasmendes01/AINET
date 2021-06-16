<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;

use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */
    //use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request)
    {
        //$user = User::find($request->id);
        $user = User::find($request->get('id'));
        dd($user);

        if ($request->route('id') != $user->getKey()) {
            throw new AuthorizationException;
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        if (!hash_equals((string) $request->get('id'), (string) $user->getKey())) {
            throw new AuthorizationException;
        }

        if (!hash_equals((string) $request->get('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            return new Response('', 204);
        }

        if ($response = $this->verified($request)) {
            return $response;
        }

        //return redirect($this->redirectPath())->with('verified', true);
        return new Response('', 204);
    }

    public function verifyIndex()
    {
        return view('auth.verify');
    }

    public function verifyUser(EmailVerificationRequest $request)
    {
        $request->fulfill();
        $id = $request->route('id');
        $user = User::findOrFail($id);
        if ($id != $user->getKey()) {
            throw new AuthorizationException;
        }
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            return redirect('/users/profile/' . $id)->with(['verified' => true, 'success' => "Account activated successfully!"]);
        }
    }

    public function resendVerificationEmail(Request $request)
    {
        $user = $request->user() ?? auth()->guard('web')->user();
        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent!');
    }
}

<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\EncomendasController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PageNotFound;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use SebastianBergmann\Environment\Console;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

/* TESTE RESET PASSWORD */
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

/* TESTE VERIFY EMAIL */
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['verify' => true]);

Route::get('/', function () {
    return redirect('shop');
});

Route::get('/login',                        [LoginController::class, 'index'])->name('login');
Route::post('/checklogin',                  [LoginController::class, 'checklogin']);
Route::post('/logout',                      [LoginController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['notSoftDeleted']], function () {
    Route::get('/users/{id}/edit',              [UserController::class, 'edit'])->name('user.edit.profile');
    Route::post('/users/{id}/edit',             [UserController::class, 'update'])->name('user.update');
    Route::post('/users/{id}/edit/checkUpdate', [UserController::class, 'checkUpdate'])->name('user.checkUpdate');

    Route::get('/encomendas',                   [EncomendasController::class, 'index'])->name('encomendas');
    Route::post('/encomendas/prepare/{orderID}', [EncomendasController::class, 'prepareOrder'])->name('encomenda.prepare');
    Route::post('/encomendas/cancel/{orderID}', [EncomendasController::class, 'cancelOrder'])->name('encomenda.cancel');
});

Route::get('/forgot_password',              [LoginController::class, 'forgotPassword']);
Route::post('/forgot_password',             [LoginController::class, 'sendPasswordResetEmail']);
Route::get('/reset_password',               [LoginController::class, 'resetPassword'])->name('reset_password');
Route::post('/reset_password/{email}',      [LoginController::class, 'saveNewPassword']);

Route::get('/signup',                       [RegisterController::class, 'index']);
Route::post('/store',                       [RegisterController::class, 'store']);

Route::group(['middleware' => ['auth', 'verified', 'notSoftDeleted']], function () {
    Route::get('/users',                        [UsersController::class, 'index'])->name('users.list');
    Route::get('/users/profile/{id}',           [UsersController::class, 'profile'])->name('user.profile');

    Route::get('/cart/checkout/{customerID}',   [CartController::class, 'checkout'])->name('cart.checkout');

    Route::get('/shop/custom',                  [ShopController::class, 'indexCustomStamp'])->name('shop.customstamp');
    Route::post('/shop/custom/new',             [ShopController::class, 'createStamp'])->name('shop.createStamp');
});

Route::group(['middleware' => ['verified', 'admin', 'notSoftDeleted']], function () {
    Route::get('/users/search',                 [UsersController::class, 'search'])->name('users.search');
    Route::post('/users/{id}/delete',           [UserController::class, 'delete'])->name('user.delete');

    Route::get('/shop/{id}/edit',               [ShopController::class, 'editEstampa'])->name('estampa.edit');
    Route::post('/shop/{id}/save',              [ShopController::class, 'saveEstampa'])->name('shop.checkUpdate');
    Route::post('/shop/{id}/delete',            [ShopController::class, 'deleteEstampa'])->name('estampa.delete');

    Route::get('/shop/statistics',              [ShopController::class, 'getStatistics'])->name('shop.statistics');
});

Route::get('/shop',                         [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{nome}/{id}',             [ShopController::class, 'product'])->name('shop.estampa');
Route::get('/shop/search',                  [ShopController::class, 'search'])->name('shop.search');

Route::group(['middleware' => ['client']], function () {
    Route::get('/cart',                         [CartController::class, 'index']);
    Route::get('/cart/add/{id}',                [ShopController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart/remove/{id}',             [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/cart/clear',                   [CartController::class, 'clearCart'])->name('cart.clear');
});

Route::get('/pagenotfound',                 [PageNotFound::class, 'error'])->name('pagenotfound');

Route::get('/estampas_privadas/{file}', [function ($file) {

    $path = storage_path('app/estampas_privadas/' . $file);

    if (file_exists($path)) {
        return response()->file($path, array('Content-Type' => 'image'));
    }

    $path = storage_path('img\navbar-logo.png');
    return response()->file($path, array('Content-Type' => 'image'));
}]);

Route::get('/storage/estampas/{file}', [function ($file) {

    $path = storage_path('/storage/estampas/' . $file);

    if (file_exists($path)) {
        return response()->file($path, array('Content-Type' => 'image/png'));
    }
    $path = storage_path('img\navbar-logo.png');
    return response()->file($path, array('Content-Type' => 'image/png'));
}]);


/* TESTE RESET PASSWORD */

Route::get('/forgot-password', function () {
    return view('auth.forgot');
})->middleware(['guest'])->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware(['guest'])->name('password.email');


Route::get('/reset-password/{email}/{token}', function ($email, $token) {
    return view('auth.new_password', ['email' => $email, 'token' => $token]);
})->middleware(['guest'])->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:3|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) use ($request) {
            $user = User::where('email', $request->email)->first();
            $user->password = $request->password;
            $user->remember_token = Str::random(60);
            $user->save();
            event(new PasswordReset($user));
        }
    );

    return $status == Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->middleware(['guest'])->name('password.update');

/* EMAIL VERIFICATION ROUTES */
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware(['auth'])->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    $id = $request->route('id');
    $user = User::find($id);
    if ($id != $user->getKey()) {
        throw new AuthorizationException;
    }
    if ($user->markEmailAsVerified()) {
        event(new Verified($user));
        return redirect('/users/profile/' . $id)->with('verified', true);
    }
})->middleware(['auth'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $user = $request->user() ?? auth()->guard('web')->user();
    $user->sendEmailVerificationNotification();

    return back()->with('success', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

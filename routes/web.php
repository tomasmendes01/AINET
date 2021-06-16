<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\VerificationController;
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
use App\Http\Controllers\StatisticsController;
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
    Route::get('/users/{id}/edit',               [UserController::class, 'edit'])->name('user.edit.profile');
    Route::put('/users/{id}/edit',               [UserController::class, 'update'])->name('user.update');
    Route::put('/users/{id}/edit/checkUpdate',   [UserController::class, 'checkUpdate'])->name('user.checkUpdate');

    Route::get('/encomendas',                    [EncomendasController::class, 'index'])->name('encomendas');
    Route::post('/encomendas/prepare/{orderID}', [EncomendasController::class, 'prepareOrder'])->name('encomenda.prepare');
    Route::post('/encomendas/cancel/{orderID}',  [EncomendasController::class, 'cancelOrder'])->name('encomenda.cancel');
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
    Route::get('/users/search',                [UsersController::class, 'search'])->name('users.search');
    Route::delete('/users/{id}/delete',        [UserController::class, 'delete'])->name('user.delete');

    Route::get('/shop/{id}/edit',              [ShopController::class, 'editEstampa'])->name('estampa.edit');
    Route::put('/shop/{id}/save',              [ShopController::class, 'saveEstampa'])->name('shop.checkUpdate');
    Route::delete('/shop/{id}/delete',         [ShopController::class, 'deleteEstampa'])->name('estampa.delete');

    Route::get('/shop/statistics',             [StatisticsController::class, 'getStatistics'])->name('shop.statistics');

    Route::get('/shop/prices',                 [ShopController::class, 'getPrices'])->name('shop.prices');
    Route::post('/shop/prices/update',         [ShopController::class, 'updatePrices'])->name('shop.prices.update');
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

Route::get('/estampas_privadas/{file}', [ShopController::class, 'getEstampaPrivada']);
Route::get('/storage/estampas/{file}',  [ShopController::class, 'getEstampaPublica']);

/* PASSWORD RESET */
Route::get('/forgot-password',                      [ForgotPasswordController::class, 'index'])->name('password.request');
Route::group(['middleware' => ['guest']], function () {
    Route::post('/forgot-password',                 [ForgotPasswordController::class, 'validateForgotPasswordRequest'])->name('password.email');
    Route::get('/reset-password/{email}/{token}',   [ForgotPasswordController::class, 'resetPasswordIndex'])->name('password.reset');
    Route::post('/reset-password',                  [ForgotPasswordController::class, 'updatePassword'])->name('password.update');
});

/* EMAIL VERIFICATION */
Route::group(['middleware' => ['auth']], function () {
    Route::get('/email/verify',                     [VerificationController::class, 'verifyIndex'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}',         [VerificationController::class, 'verifyUser'])->name('verification.verify')->middleware('signed');
    Route::post('/email/verification-notification', [VerificationController::class, 'resendVerificationEmail'])->middleware('throttle:6,1')->name('verification.resend');
});

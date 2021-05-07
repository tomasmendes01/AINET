<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\EncomendasController;
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
use Illuminate\Support\Facades\Request;
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

Route::get('/', function () {
    return redirect('shop');
});


Route::get('/login',            [LoginController::class, 'index'])->name('login');
Route::post('/checklogin',      [LoginController::class, 'checklogin']);
Route::get('/logout',           [LoginController::class, 'logout'])->name('logout');

Route::get('/forgot_password',  [LoginController::class, 'forgotPassword']);
Route::post('/forgot_password', [LoginController::class, 'sendPasswordResetEmail']);

Route::get('/reset_password',   [LoginController::class, 'resetPassword'])->name('reset_password');
Route::post('/reset_password/{email}',[LoginController::class, 'saveNewPassword']);

Route::get('/signup',           [RegisterController::class, 'index']);
Route::post('/store',           [RegisterController::class, 'store']);

Route::get('/users',            [UsersController::class, 'index'])->name('users.list');
Route::get('/users/profile/{id}',[UsersController::class, 'profile'])->name('user.profile');
Route::get('/users/search',     [UsersController::class, 'search'])->name('users.search');
Route::get('/users/{id}/edit',  [UserController::class, 'edit'])->name('user.edit.profile');
Route::post('/users/{id}/edit', [UserController::class, 'update'])->name('user.update');
Route::post('/users/{id}/edit/checkUpdate', [UserController::class, 'checkUpdate'])->name('user.checkUpdate');
Route::post('/users/{id}/delete',[UserController::class, 'delete'])->name('user.delete');
//Route::post('/users/{id}/blockunblock', [UserController::class, 'blockunblock'])->name('user.blockunblock');

Route::get('/shop',             [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{nome}/{id}', [ShopController::class, 'product'])->name('shop.estampa');
Route::get('/shop/search',      [ShopController::class, 'search'])->name('shop.search');
Route::get('/shop/custom',      [ShopController::class, 'indexCustomStamp'])->name('shop.customstamp');

Route::get('/cart',             [CartController::class, 'index']);

Route::get('/pagenotfound',     [PageNotFound::class, 'error'])->name('pagenotfound');

Route::get('/encomendas',       [EncomendasController::class, 'index'])->name('encomendas');

/*
Route::get('/add-to-cart/{id}', [
    'uses' => CartController::class, 'add',
    'as'   => 'product.addToCart'
]);
*/

Route::get('/estampas_privadas/{file}', [function ($file) {

    $path = storage_path('app/estampas_privadas/' . $file);

    if (file_exists($path)) {
        return response()->file($path, array('Content-Type' => 'image/png'));
    }

    $path = storage_path('img\navbar-logo.png');
    return response()->file($path, array('Content-Type' => 'image/png'));
}]);

Route::get('/storage/estampas/{file}', [function ($file) {

    $path = storage_path('/storage/estampas/' . $file);

    if (file_exists($path)) {
        return response()->file($path, array('Content-Type' => 'image/png'));
    }
    $path = storage_path('img\navbar-logo.png');
    return response()->file($path, array('Content-Type' => 'image/png'));
}]);

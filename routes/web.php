<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\EncomendasController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use League\CommonMark\Inline\Element\Image;
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

/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/', function () {
    return redirect('shop');
});


Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/checklogin', [LoginController::class, 'checklogin']);

Route::get('/logout', [LoginController::class, 'logout']);
Route::get('/forgotpassword', [LoginController::class, 'sendPasswordResetNotification']);

Route::get('/signup', [RegisterController::class, 'index']);
Route::post('/store', [RegisterController::class, 'store']);

Route::get('/users', [UsersController::class, 'index']);

Route::get('/shop',      [ShopController::class, 'index']);
Route::get('/shop/{id}', [ShopController::class, 'filter_by_category']);

Route::get('/cart', [CartController::class, 'index']);
/*
Route::get('/add-to-cart/{id}', [
    'uses' => CartController::class, 'add',
    'as'   => 'product.addToCart'
]);
*/

Route::get('/encomendas', [EncomendasController::class, 'index']);

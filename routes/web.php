<?php

use App\Http\Controllers\RecipesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PiscicultorController;
use App\Http\Controllers\AlevinController;
use App\Http\Controllers\FeedingController;
use App\Http\Controllers\IceController;
use App\Http\Controllers\HarvestController;
use App\Http\Controllers\SanitaryController;
use App\Http\Controllers\WeightController;
use App\Http\Controllers\PondController;
use App\Http\Controllers\SpecieController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\FoodbrandController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
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

Route::get('user/report', [PiscicultorController::class, 'globalReport'])->middleware('auth');
Route::resource('user', PiscicultorController::class)->middleware('auth')->except(['globalReport']);
Route::get('user/report/{id}', [PiscicultorController::class, 'report'])->middleware('auth');
Route::get('user/edit/password/{id}', [PiscicultorController::class, 'changePassword'])->middleware('auth');

Route::resource('pond', PondController::class)->middleware('auth');
Route::get('pond/report/{id}', [PondController::class, 'report'])->middleware('auth');

Route::resource('alevin', AlevinController::class)->middleware('auth');
Route::get('alevin/create/{id}', [AlevinController::class, 'create'])->middleware('auth');

Route::resource('feeding', FeedingController::class)->middleware('auth');
Route::get('feeding/create/{id}', [FeedingController::class, 'create'])->middleware('auth');

Route::resource('ice', IceController::class)->middleware('auth');
Route::get('ice/create/{id}', [IceController::class, 'create'])->middleware('auth');

route::resource('harvest', HarvestController::class)->middleware('auth');
route::get('harvest/create/{id}', [HarvestController::class, 'create'])->middleware('auth');

route::resource('sanitary', SanitaryController::class)->middleware('auth');
route::get('sanitary/create/{id}', [SanitaryController::class, 'create'])->middleware('auth');

route::resource('weight', WeightController::class)->middleware('auth');
route::get('weight/create/{id}', [WeightController::class, 'create'])->middleware('auth');

route::resource('species', SpecieController::class)->middleware('auth');

route::resource('provider', ProviderController::class)->middleware('auth');

route::resource('foodbrand', FoodbrandController::class)->middleware('auth');

route::resource('blog', BlogController::class)->middleware('auth');

route::resource('product', ProductController::class)->middleware('auth');

route::resource('recipes', RecipesController::class)->middleware('auth');

Auth::routes();//['register'=>false,'reset'=>false]

Route::get('/', function () {
    return view('auth.login');
});


Route::group(['middleware' => 'auth'], function () {

    Route::get('/admin', [PiscicultorController::class, 'index'])->name('admin');
    Route::get('/home', [PondController::class, 'index'])->name('home');
});

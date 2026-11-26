<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Farmer;
use App\Http\Controllers\SiteController;
use App\Models\Pond;
use Illuminate\Support\Facades\Route;

Route::controller(SiteController::class)->group(function (): void {
    Route::get('/', 'home')->name('home');
    Route::get('/productos', 'products')->name('site.products');
    Route::get('/recetas', 'recipes')->name('site.recipes');
    Route::get('/recetas/{recipe:slug}', 'recipe')->name('site.recipe');
    Route::get('/noticias', 'posts')->name('site.posts');
    Route::get('/noticias/{post:slug}', 'post')->name('site.post');
    Route::get('/sitemap.xml', 'sitemap')->name('sitemap');
    Route::get('/robots.txt', 'robots')->name('robots');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/ingresar', [LoginController::class, 'create'])->name('login');
    Route::post('/ingresar', [LoginController::class, 'store'])->middleware('throttle:login');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/salir', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/cuenta/clave', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/cuenta/clave', [PasswordController::class, 'update'])->name('password.update');

    Route::get('/estanques/{pond}', [Farmer\PondController::class, 'show'])->name('ponds.show')->can('view', 'pond');
    Route::get('/ciclos/{cycle}', [Farmer\CycleController::class, 'show'])->name('cycles.show')->can('view', 'cycle');
    Route::get('/ciclos/{cycle}/reporte', [Farmer\CycleController::class, 'report'])->name('cycles.report')->can('view', 'cycle');
});

Route::middleware(['auth', 'role:piscicultor'])->group(function (): void {
    Route::get('/panel', Farmer\DashboardController::class)->name('farmer.dashboard');

    Route::get('/estanques', [Farmer\PondController::class, 'index'])->name('ponds.index');
    Route::get('/estanques/nuevo', [Farmer\PondController::class, 'create'])->name('ponds.create')->can('create', Pond::class);
    Route::post('/estanques', [Farmer\PondController::class, 'store'])->name('ponds.store')->can('create', Pond::class);
    Route::get('/estanques/{pond}/editar', [Farmer\PondController::class, 'edit'])->name('ponds.edit')->can('update', 'pond');
    Route::put('/estanques/{pond}', [Farmer\PondController::class, 'update'])->name('ponds.update')->can('update', 'pond');

    Route::get('/estanques/{pond}/siembra', [Farmer\CycleController::class, 'create'])->name('cycles.create')->can('stock', 'pond');
    Route::post('/estanques/{pond}/siembra', [Farmer\CycleController::class, 'store'])->name('cycles.store')->can('stock', 'pond');

    Route::middleware('can:log,cycle')->scopeBindings()->group(function (): void {
        Route::post('/ciclos/{cycle}/cierre', [Farmer\CycleController::class, 'close'])->name('cycles.close');
        Route::controller(Farmer\LogbookController::class)->group(function (): void {
            Route::post('/ciclos/{cycle}/alimentaciones', 'storeFeeding')->name('feedings.store');
            Route::delete('/ciclos/{cycle}/alimentaciones/{feeding}', 'destroyFeeding')->name('feedings.destroy');
            Route::post('/ciclos/{cycle}/biometrias', 'storeSampling')->name('samplings.store');
            Route::delete('/ciclos/{cycle}/biometrias/{sampling}', 'destroySampling')->name('samplings.destroy');
            Route::post('/ciclos/{cycle}/tratamientos', 'storeTreatment')->name('treatments.store');
            Route::delete('/ciclos/{cycle}/tratamientos/{treatment}', 'destroyTreatment')->name('treatments.destroy');
            Route::post('/ciclos/{cycle}/cosechas', 'storeHarvest')->name('harvests.store');
            Route::delete('/ciclos/{cycle}/cosechas/{harvest}', 'destroyHarvest')->name('harvests.destroy');
        });
    });
});

Route::middleware(['auth', 'role:administrador'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reporte', [Admin\DashboardController::class, 'report'])->name('report');

    Route::resource('piscicultores', Admin\FarmerController::class)
        ->except('destroy')->names('farmers')->parameters(['piscicultores' => 'farmer']);
    Route::resource('especies', Admin\SpeciesController::class)
        ->except('show')->names('species')->parameters(['especies' => 'species']);
    Route::resource('alimentos', Admin\FeedBrandController::class)
        ->except('show')->names('feed-brands')->parameters(['alimentos' => 'feed_brand']);
    Route::resource('proveedores', Admin\ProviderController::class)
        ->except('show')->names('providers')->parameters(['proveedores' => 'provider']);
    Route::resource('noticias', Admin\PostController::class)
        ->except('show')->names('posts')->parameters(['noticias' => 'post']);
    Route::resource('productos', Admin\ProductController::class)
        ->except('show')->names('products')->parameters(['productos' => 'product']);
    Route::resource('recetas', Admin\RecipeController::class)
        ->except('show')->names('recipes')->parameters(['recetas' => 'recipe']);
});

Route::permanentRedirect('/home', '/panel');
Route::permanentRedirect('/login', '/ingresar');

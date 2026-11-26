<?php

namespace App\Providers;

use App\Models\User;
use App\Support\Format;
use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    private const array NUMERIC_PARAMETERS = ['pond', 'cycle', 'feeding', 'sampling', 'treatment', 'harvest', 'farmer', 'species', 'feed_brand', 'provider'];

    public function register(): void
    {
        $this->app->singleton(Format::class);
    }

    public function boot(): void
    {
        Date::use(CarbonImmutable::class);
        Paginator::defaultView('partials.pagination');
        Password::defaults(fn () => Password::min(10)->letters()->numbers());
        View::share('fmt', $this->app->make(Format::class));

        Route::resourceVerbs(['create' => 'nuevo', 'edit' => 'editar']);
        foreach (self::NUMERIC_PARAMETERS as $parameter) {
            Route::pattern($parameter, '[0-9]+');
        }
        Route::bind('farmer', fn (string $id) => User::query()->farmers()->findOrFail($id));

        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(20)->by($request->ip()));
    }
}

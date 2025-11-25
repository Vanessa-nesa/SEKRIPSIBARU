<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * 🔹 Halaman Login & Register (Blade)
         */
        Fortify::loginView(function () {
            return view('login'); 
        });

        Fortify::registerView(function () {
            return view('regis'); 
        });

        /**
         * 🔹 Action Fortify
         */
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);

        /**
         * 🔹 RateLimiter LOGIN
         */
        RateLimiter::for('login', function (Request $request) {
            $username = (string) $request->input(Fortify::username());

            return Limit::perMinute(5)->by(
                $username.'|'.$request->ip()
            );
        });

        /**
         * 🔹 RateLimiter TWO FACTOR AUTH
         */
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by(
                $request->session()->get('login.id')
            );
        });
    }
}

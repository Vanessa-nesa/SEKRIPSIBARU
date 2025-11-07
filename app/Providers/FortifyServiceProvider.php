<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
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
         * 🔹 Definisikan tampilan untuk halaman login & register
         */
        Fortify::loginView(function () {
            return view('login'); // pastikan file: resources/views/login.blade.php
        });

        Fortify::registerView(function () {
            return view('regis'); // pastikan file: resources/views/regis.blade.php
        });

        /**
         * 🔹 Tambahkan RateLimiter untuk LOGIN
         * Supaya error “Rate limiter [login] is not defined” hilang
         */
        RateLimiter::for('login', function (Request $request) {
            // Maksimum 5 percobaan login per menit per IP / username
            $username = (string) $request->input('username');

            return Limit::perMinute(5)->by($username . $request->ip());
        });

        /**
         * 🔹 Rate limiter untuk 2FA (opsional, aman dibiarkan)
         */
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}

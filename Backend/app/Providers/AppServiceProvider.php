<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
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
        Gate::define('update-order', function ($user) {
            return $user->role === 'Administrator' || $user->role === 'staff';
        });

        Gate::define('delete-order', function ($user) {
            return $user->role === 'Administrator' || $user->role === 'staff';
        });
        
        

        /* sementara
        *if (str_contains(request()->url(), 'ngrok-free.dev')) {
        *    \Illuminate\Support\Facades\URL::forceScheme('https');
        }*/
        
    }
}

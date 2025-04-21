<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
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
        //Paginator::useBootstrapFive();
        //Paginator::useBootstrapFour();

        Gate::define('manage-assistance', function (User $user) {
            return $user->is_admin;
        });

        Gate::define('manage-own-assistance', function (User $user, AssistanceTeacher $assistance) {
            return $user->id === $assistance->user_id;
        });

        Gate::define('manage-owner', function (User $user, User $another) {
            return $user->id === $another->id;
        });
    }
}

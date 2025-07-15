<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('cgi_only', function ($user) {
            return in_array($user->role, [2,5]);
        });

        Gate::define('admin_lvl1', function ($user) {
            return in_array($user->role, [3, 4, 5]);
        });

        Gate::define('admin_lvl2', function ($user) {
            return in_array($user->role, [4, 5]);
        });

        Gate::define('admin_lvl3', function ($user) {
            return $user->role == 5;
        });

        Gate::define('sp_only', function ($user) {
            return in_array($user->role, [0]);
        });

        Gate::define('sp_fi_only', function ($user) {
            return in_array($user->role, [0, 1, 5]);
        });

        Gate::define('not_for_sp_fi', function ($user) {
            return in_array($user->role, [2, 3, 4, 5]);
        });

        Gate::define('not_for_sp', function ($user) {
            return in_array($user->role, [1, 2, 3, 4, 5]);
        });
        Gate::define('fi_only', function ($user) {
            return in_array($user->role, [1]);
        });
    }
}

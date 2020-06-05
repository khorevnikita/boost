<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


        $policies = [
            'orders' => ['sales', 'admin'],
            'content' => ['content', 'admin'],
            'users' => ['admin']
        ];

        Gate::define('update-orders', function ($user) use ($policies) {
            return in_array($user->role,$policies['orders']) ;
        });

        Gate::define('update-content', function ($user) use ($policies) {
            return in_array($user->role,$policies['content']);
        });

        Gate::define('update-users', function ($user) use ($policies) {
            return in_array($user->role,$policies['users']);
        });

    }
}

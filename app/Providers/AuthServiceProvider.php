<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
        Gate::define('admin', function(User $user)
        {
            return $user->role === 'admin';
        });
        Gate::define('guru', function(User $user)
        {
            return $user->role === 'guru';
        });
        Gate::define('pakar', function(User $user)
        {
            return $user->role === 'pakar';
        });
        Gate::define('pelajar', function(User $user)
        {
            return $user->role === 'pelajar';
        });
    }
}

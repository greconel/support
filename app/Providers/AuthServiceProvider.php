<?php

namespace App\Providers;

use App\Models\Email;
use App\Models\Implementation;
use App\Policies\ActivityPolicy;
use App\Policies\EmailPolicy;
use App\Policies\ImplementationPolicy;
use App\Policies\PassportClientPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
        Client::class => PassportClientPolicy::class,
        Activity::class => ActivityPolicy::class,
        Email::class => EmailPolicy::class,
        Implementation::class => ImplementationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Note: Passport routes are auto-registered in Passport v12+

        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addYears(2));

        Passport::tokensCan([
            // 'scope-name' => 'Scope description',
            // 'clients-get' => 'Get all clients',
            // 'clients-post' => 'Create new client'
        ]);

        Passport::setDefaultScope([

        ]);

        // Implicitly grant "super admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super admin') ? true : null;
        });
    }
}

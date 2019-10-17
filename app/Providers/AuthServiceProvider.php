<?php

namespace App\Providers;

use App\Http\Middleware\ownerEventMiddleware;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('event-owner', function ($user, $event) {

            if ($user->can('all-access')) {

                return TRUE;

            }else{

                return $user->group->id === $event->group_id;

            }
            // @can("event.owner", $actual_user, $event)
            // @if ($event->group_id == $actual_user->group->id)
        });

    }
}

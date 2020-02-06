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

        // Saber si un usuario es propietario de un evento en base a su grupo
        Gate::define('event.owner', function ($user, $event) {

            if ($user->can('all-access')) { //Webmaster

                return TRUE;

            }else{

                // true si el id de grupo es igual a el grupo asignado al evento
                return $user->group->id === $event->group_id;

            }           
            // @can("event.owner", $actual_user, $event)
            // @if ($event->group_id == $actual_user->group->id)
        });


        // Usuario puede editar eventos
        Gate::define('event.edit', function ($user, $event) {

            # Traer roles de usuario auth
            $user_roles = auth()->user()->roles->pluck('slug');

            if ($user_roles) {

                if ( in_array("admin", $user_roles->toArray()) ) {

                    return TRUE;

                } else if(in_array("owner", $user_roles->toArray()) ) {

                    return TRUE;

                } else if(in_array("event-coordinator", $user_roles->toArray()) ) {

                    return TRUE;

                } else if (in_array("event-editor", $user_roles->toArray()) ) {

                    // puede editar si el id de grupo es igual a el grupo asignado al evento
                    return $user->group->id === $event->group_id;

                } else if (in_array("event-collaborator", $user_roles->toArray()) ) {

                    // puede editar si es de grupo propio y si aun no esta publicado
                    if ( ($event->state != 1) && $user->group->id === $event->group_id ) {
                        return TRUE;
                    }

                }
            }
            
            return FALSE;

        });


        // Usuario puede publicar eventos
        Gate::define('event.publish', function ($user, $event) {

            # Traer roles de usuario auth
            $user_roles = auth()->user()->roles->pluck('slug');

            if ($user_roles) {

                if ( in_array("admin", $user_roles->toArray()) ) {

                    return TRUE;

                } else if(in_array("owner", $user_roles->toArray()) ) {

                    return TRUE;

                } else if(in_array("event-coordinator", $user_roles->toArray()) ) {

                    return TRUE;

                } else if (in_array("event-editor", $user_roles->toArray()) ) {

                    // puede editar si el id de grupo es igual a el grupo asignado al evento
                    return $user->group->id === $event->group_id;

                } else if (in_array("event-collaborator", $user_roles->toArray()) ) {

                    return FALSE;

                }
            }
            
            return FALSE;

        });


        // Usuario puede asociar eventos
        Gate::define('event.associate', function ($user, $event) {

            # Traer roles de usuario auth
            $user_roles = auth()->user()->roles->pluck('slug');

            if ($user_roles) {

                if ( in_array("admin", $user_roles->toArray()) ) {

                    # No permitir desvincular si el evento es propio
                    return $user->group->id != $event->group_id;

                } else if (in_array("owner", $user_roles->toArray()) ) {

                    # No permitir desvincular si el evento es propio
                    return $user->group->id != $event->group_id;

                } else if(in_array("event-coordinator", $user_roles->toArray()) ) {

                    # No permitir desvincular si el evento es propio
                    return $user->group->id != $event->group_id;

                } else if (in_array("event-editor", $user_roles->toArray()) ) {

                    return FALSE;

                } else if (in_array("event-collaborator", $user_roles->toArray()) ) {

                    return FALSE;

                }
            }
            
            return FALSE;
                  
        });


        // Usuario puede borrar eventos ?
        Gate::define('event.delete', function ($user, $event) {

            # Traer roles de usuario auth
            $user_roles = auth()->user()->roles->pluck('slug');

            // var_dump(auth()->hasRole('admin')); exit(); # metodo de shinobi 4

            if ($user_roles) {
                // $isEditor = auth()->hasRole('admin');
                if ( in_array("admin", $user_roles->toArray()) ) {

                    return TRUE;

                } else if(in_array("owner", $user_roles->toArray()) ) {

                    return TRUE;

                } else if(in_array("event-coordinator", $user_roles->toArray()) ) {

                    return TRUE;

                } else if (in_array("event-editor", $user_roles->toArray()) ) {

                    // puede borrar si el id de grupo es igual a el grupo asignado al evento
                    return $user->group->id === $event->group_id;

                } else if (in_array("event-collaborator", $user_roles->toArray()) ) {

                    // puede borrar si es de grupo propio y si aun no esta publicado
                    if ( ($event->state != 1) && $user->group->id === $event->group_id ) {
                        return TRUE;
                    }

                }
            }
            
            return FALSE;

        });

    }
}

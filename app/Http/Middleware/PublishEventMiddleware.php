<?php

namespace App\Http\Middleware;

use Closure;

use App\Event;

# Autentificacion de usuarios
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Gate;

class PublishEventMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        
        $event = Event::find($request->route('event'));

        // echo ('<pre>');print_r($event->group_id);echo ('</pre>');
        // echo ('<pre>');print_r(auth()->user()->group_id);echo ('</pre>');

        if (!$event) {

            return back()->withErrors('Evento inexistente');
            
        } else {
            
            if (!Gate::allows('event.publish', $event) ) {

                if ($request->state == 1) {

                    return back()->withErrors('No esta autorizado a publicar este Evento');
                }
            }

        }        
        
        return $next($request);
    }
}

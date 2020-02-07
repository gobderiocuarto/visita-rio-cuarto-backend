<?php

namespace App\Http\Middleware;

use Closure;

use App\Event;

# Autentificacion de usuarios
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Gate;

class AssociateEventMiddleware
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

        if (!$event) {

            return redirect()->route('events.index')->withErrors('Evento inexistente');
            
        } else {
            
            if (!Gate::allows('event.associate', $event) ) {
                return redirect()->route('events.index')->withErrors('No esta autorizado a Asociar este Evento a su portal');
                
            }

        }        
        
        return $next($request);
    }
}

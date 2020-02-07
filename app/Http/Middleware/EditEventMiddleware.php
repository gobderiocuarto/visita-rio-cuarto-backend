<?php

namespace App\Http\Middleware;

use Closure;

use App\Event;

# Autentificacion de usuarios
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Gate;

class EditEventMiddleware
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

        // echo ('<pre>');print_r($event);echo ('</pre>'); exit();

        if (!$event) {

            return redirect()->route('events.index')->withErrors('Evento inexistente');
            
        } else {
            
            if (!Gate::allows('event.edit', $event) ) {
                return redirect()->route('events.index')->withErrors('No esta autorizado a editar este Evento');
                
            }

        }  
        
        return $next($request);
    }
}

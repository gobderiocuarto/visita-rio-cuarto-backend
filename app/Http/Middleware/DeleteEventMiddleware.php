<?php

namespace App\Http\Middleware;

use Closure;

use App\Event;

# Autentificacion de usuarios
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Gate;

class DeleteEventMiddleware
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

            
            # Usuario webmaster o propietario de evento
            if (!Gate::allows('event.delete', $event) ) {
                return back()->withErrors('No esta autorizado a eliminar este Evento');
            }

        }        
        
        return $next($request);
    }
}

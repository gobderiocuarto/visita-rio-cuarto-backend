<?php

namespace App\Http\Middleware;

use Closure;

# Autentificacion de usuarios
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Gate;

class CreateEventFrameMiddleware
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

        if ($request->rel_frame) { 

            if ($request->rel_frame == 'is-frame') { # Definido como evento marco

                # Chequear que el usuario este autorizado a crear eventos marco
                if ( (!Gate::allows('event.createFrame')) ) {
                    return back()->withErrors('Usuario NO autorizado a crear eventos marco');
                }
            }
        }
        
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;

# Autentificacion de usuarios
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Gate;

class EditEventGroupMiddleware
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
        if ( (!Gate::allows('event.editGroup')) && (Auth::user()->group_id != $request->group_id) ) {
            return back()->withErrors('No puede asignar al evento un grupo diferente al que Ud. pertenece');
        }
        
        return $next($request);
    }
}

<?php
namespace App\Http\Middleware;

use Closure;

use App\Event;

# Autentificacion de usuarios
use Illuminate\Support\Facades\Auth;

class ownerEventMiddleware
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
        
        if ($this->eventOwner($request)) {

            return $next($request);

        } else {

            abort(403, 'Acción no autorizada');

        }

    }



    public function eventOwner($request)
    {
        
        $event = Event::find($request->route('event'));
        if ($event) {

            # Usuario webmaster o propietario de evento
            if ( (Auth::user()->can('all-access')) || (Auth::user()->group->id === $event->group_id) ) {
                return TRUE;
            }

        }

        return FALSE; 

    }
    
}

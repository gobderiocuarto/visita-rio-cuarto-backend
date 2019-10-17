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

        $group_id =  Auth::user()->group->id;
        
        $event = Event::findOrFail($request->route('edit_id'));

        if ($event->group_id != Auth::user()->group->id) {

            // return redirect('home');
            abort(403, 'Acción no autorizada');       
        }

        return $next($request);
    }
}

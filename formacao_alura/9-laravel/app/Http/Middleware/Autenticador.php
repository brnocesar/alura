<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Autenticador
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
        if ( !$request->is('entrar', 'registrar', 'series', '*/temporadas', '*/episodios') and !Auth::check() ) {
            return redirect()->route('pagina_login');
        }

        return $next($request);
    }
}

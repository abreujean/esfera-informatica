<?php

namespace App\Http\Middleware;

use App\Http\Controllers\ProfileController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        if (auth()->user()->profile_id != ProfileController::$ADMINISTRADOR) {
            return redirect('/dashboard');
        }

        return $next($request);
    }
}

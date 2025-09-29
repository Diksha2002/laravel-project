<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserBelongsToShop
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): \Symfony\Component\HttpFoundation\Response  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            // If not authenticated, just continue (or you can redirect/login)
            return redirect()->route('login');
        }

        // For example, if the route has a resource with shop_id parameter,
        // you can check if the user has access.
        // But often, you enforce this at query level (in controllers or models)

        // For now, just let the request pass:
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SocietyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->token;

        $society = \App\Models\Society::where('login_tokens', $token)->first();
        if (!$society) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        $request->merge(['society' => $society]);
        return $next($request);
    }
}

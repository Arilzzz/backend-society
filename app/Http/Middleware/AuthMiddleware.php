<?php

namespace App\Http\Middleware;

use App\Models\Society;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // ambil token dari query param
        $token = $request->token;

        // cek token ke database
        $society = Society::where('login_tokens', $token)->first();

        // kalau token tidak valid
        if (!$society) {
            return response()->json([
                'message' => 'Unauthorized user'
            ], 401);
        }

        // simpan data society ke request
        $request->society = $society;

        return $next($request);
    }
}

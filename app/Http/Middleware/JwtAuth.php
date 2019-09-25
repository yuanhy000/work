<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class JwtAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->guard('api')->user();
        if($user){
            return $next($request);
        }
        return response()->json([
            'error' => 'Forbidden'
        ],403);

    }
}

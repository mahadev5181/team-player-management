<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\Controller;

class Role extends Controller
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = auth()->user();

        if(in_array($user->role_id,$roles)){
            return $next($request);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Sorry!! Access Denied'
        ], 500);
    }
}
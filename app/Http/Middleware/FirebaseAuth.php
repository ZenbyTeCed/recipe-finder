<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FirebaseAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('firebase_uid')) {
            return redirect('/login');
        }

        return $next($request);
    }
}
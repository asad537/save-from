<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->get('admin_authenticated')) {
            return redirect()->guest(route('admin.login'));
        }

        return $next($request);
    }
}

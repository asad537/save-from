<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * "/index.php" and "/index.php/anything" render the same page as the clean
 * URL and were getting their own canonical, so they are sent to the clean URL.
 */
class RedirectIndexPhp
{
    public function handle(Request $request, Closure $next)
    {
        $uri = $request->getRequestUri();

        if ($request->isMethod('GET') && preg_match('#^/index\.php(?=/|\?|$)#i', $uri)) {
            $clean = preg_replace('#^/index\.php#i', '', $uri);
            if ($clean !== '' && $clean[0] === '?') {
                $clean = '/'.$clean;
            }

            return redirect()->to($request->getSchemeAndHttpHost().$clean, 301);
        }

        return $next($request);
    }
}

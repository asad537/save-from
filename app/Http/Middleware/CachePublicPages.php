<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

/**
 * Serves rendered public pages from the cache store so a visit does not
 * rebuild the page from the database on every request.
 *
 * Runs last in the "web" group, so sessions, CSRF and visit tracking still
 * run on every request; only the controller/view work is skipped on a hit.
 * Pages are never cached (or served from cache) for a request that carries
 * session state the page would render: a downloader result, validation
 * errors, old input, or an admin login.
 */
class CachePublicPages
{
    /** Seconds a rendered page stays cached. Deploys run cache:clear. */
    const TTL = 900;

    /** Paths (prefixes) that must always be generated. */
    const BYPASS = ['admin', 'analyze', 'download-file', 'prepare-download', 'sitemap.xml', 'robots.txt'];

    /** Query parameters that may vary a cached page. Anything else bypasses the cache. */
    const ALLOWED_QUERY = ['page'];

    public function handle(Request $request, Closure $next)
    {
        if (!$this->cacheable($request)) {
            return $next($request);
        }

        $key = 'page:'.sha1($request->getHost().'|'.$request->getRequestUri());
        $cached = Cache::get($key);
        if (is_array($cached) && isset($cached['body'])) {
            return response($cached['body'], 200, $cached['headers'])->header('X-Page-Cache', 'HIT');
        }

        $response = $next($request);

        if ($response instanceof Response
            && $response->getStatusCode() === 200
            && stripos((string) $response->headers->get('Content-Type'), 'text/html') !== false) {
            Cache::put($key, [
                'body' => $response->getContent(),
                'headers' => ['Content-Type' => $response->headers->get('Content-Type')],
            ], self::TTL);
            $response->headers->set('X-Page-Cache', 'MISS');
        }

        return $response;
    }

    private function cacheable(Request $request): bool
    {
        if (!config('app.page_cache', true) || !$request->isMethod('GET')) {
            return false;
        }
        foreach (self::BYPASS as $prefix) {
            if ($request->is($prefix) || $request->is($prefix.'/*')) {
                return false;
            }
        }
        if (array_diff(array_keys($request->query()), self::ALLOWED_QUERY)) {
            return false;
        }
        if ($request->hasSession()) {
            $session = $request->session();
            if ($session->has('download_result') || $session->has('errors') || $session->hasOldInput() || $session->get('admin_authenticated')) {
                return false;
            }
        }

        return true;
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\AnalyticsEvent;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class TrackVisit
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->isMethod('GET')
            && !$request->is('admin*')
            && !$request->is('prepare-download*')
            && !$request->is('download-file*')
            && !$request->is('robots.txt')
            && !$request->is('sitemap.xml')
            && $response->getStatusCode() < 400) {
            try {
                if (Schema::hasTable('analytics_events')) {
                    AnalyticsEvent::create([
                        'event_type' => 'visit',
                        'ip_address' => $request->ip(),
                        'user_agent' => substr((string) $request->userAgent(), 0, 1000),
                        'url' => substr((string) $request->fullUrl(), 0, 2048),
                        'referrer' => substr((string) $request->headers->get('referer'), 0, 2048),
                        'created_at' => now(),
                    ]);
                }
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        return $response;
    }
}

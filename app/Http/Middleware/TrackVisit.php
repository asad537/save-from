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
                    $visitorKey = $request->session()->get('analytics_visitor_key');
                    $visitorToken = (string) $request->cookie('sf_visitor');
                    $shouldSetVisitorCookie = !preg_match('/^[a-f0-9]{40}$/', $visitorToken);

                    if ($shouldSetVisitorCookie) {
                        $visitorToken = bin2hex(random_bytes(20));
                    }

                    if (!$visitorKey) {
                        $visitorKey = hash('sha256', $visitorToken);
                        $request->session()->put('analytics_visitor_key', $visitorKey);
                    }

                    AnalyticsEvent::create([
                        'event_type' => 'page_view',
                        'visitor_key' => $visitorKey,
                        'ip_address' => $request->ip(),
                        'user_agent' => substr((string) $request->userAgent(), 0, 1000),
                        'url' => substr((string) $request->fullUrl(), 0, 2048),
                        'referrer' => substr((string) $request->headers->get('referer'), 0, 2048),
                        'created_at' => now(),
                    ]);

                    $visitedToday = AnalyticsEvent::where('event_type', 'visit')
                        ->where('visitor_key', $visitorKey)
                        ->whereDate('created_at', now()->toDateString())
                        ->exists();

                    if (!$visitedToday) {
                        AnalyticsEvent::create([
                            'event_type' => 'visit',
                            'visitor_key' => $visitorKey,
                            'ip_address' => $request->ip(),
                            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
                            'url' => substr((string) $request->fullUrl(), 0, 2048),
                            'referrer' => substr((string) $request->headers->get('referer'), 0, 2048),
                            'created_at' => now(),
                        ]);
                    }

                    if ($shouldSetVisitorCookie) {
                        $response->withCookie(cookie('sf_visitor', $visitorToken, 525600, '/', null, $request->isSecure(), true, false, 'lax'));
                    }
                }
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        return $response;
    }
}

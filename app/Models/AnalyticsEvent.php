<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AnalyticsEvent extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'event_type', 'visitor_key', 'ip_address', 'user_agent', 'url', 'referrer',
        'platform', 'media_title', 'format', 'quality', 'created_at',
    ];

    protected $dates = ['created_at'];

    public static function recordDownload(Request $request, array $meta)
    {
        return static::create([
            'event_type' => 'download',
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
            'url' => substr((string) $request->fullUrl(), 0, 2048),
            'referrer' => substr((string) $request->headers->get('referer'), 0, 2048),
            'platform' => substr((string) ($meta['platform'] ?? ''), 0, 120),
            'media_title' => substr((string) ($meta['title'] ?? ''), 0, 500),
            'format' => substr((string) ($meta['format'] ?? ''), 0, 30),
            'quality' => substr((string) ($meta['quality'] ?? ''), 0, 60),
            'created_at' => now(),
        ]);
    }
}

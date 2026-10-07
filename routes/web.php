<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DownloaderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\SupportedSiteController as AdminSupportedSiteController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\HomepageContentController as AdminHomepageContentController;
use App\Models\BlogPost;
use App\Models\SupportedSite;
use App\Models\AnalyticsEvent;
use App\Models\Faq;
use App\Models\LandingPage;
use App\Models\HomepageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\SitemapGenerator;

Route::get('/', function () {
    return view('home', [
        'homepageContent' => HomepageContent::where('is_active', true)->first(),
        'platforms' => SupportedSite::active()->orderBy('sort_order')->orderBy('name')->get()->map(function ($site) {
            return ['name' => $site->name, 'slug' => $site->slug, 'logo' => $site->logo_url, 'icon' => $site->brandIcon(), 'color' => $site->brandColor()];
        }),
        'latestPosts' => BlogPost::published()->latest('published_at')->latest('id')->limit(4)->get(),
    ]);
})->name('home');

Route::post('/analyze', [DownloaderController::class, 'analyze'])->middleware('throttle:20,1')->name('downloader.analyze');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->middleware('throttle:5,1')->name('authenticate');
    Route::middleware('admin.auth')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::resource('blog', AdminBlogPostController::class)->except(['show'])->parameters(['blog' => 'post']);
        Route::resource('sites', AdminSupportedSiteController::class)->except(['show']);
        Route::resource('faqs', AdminFaqController::class)->except(['show']);
        Route::get('/homepage-content', [AdminHomepageContentController::class, 'edit'])->name('homepage.edit');
        Route::put('/homepage-content', [AdminHomepageContentController::class, 'update'])->name('homepage.update');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    });
});

Route::get('/download-file/{token}', function (Request $request, string $token) {
    $download = Cache::get('direct_download:'.$token);
    if (!$download || empty($download['url']) || !filter_var($download['url'], FILTER_VALIDATE_URL)) {
        return response()->view('download-error', ['message' => 'This download request has expired. Please analyze the link again.'], 410);
    }

    $url = $download['url'];
    $host = strtolower((string) parse_url($url, PHP_URL_HOST));
    if (parse_url($url, PHP_URL_SCHEME) !== 'https' || !($host === 'vidssave.com' || Str::endsWith($host, '.vidssave.com'))) {
        Log::warning('Blocked an unexpected media download host', ['host' => $host]);
        return response()->view('download-error', ['message' => 'The media provider returned an invalid download address. Please analyze the link again.'], 502);
    }

    $headers = [
        'User-Agent' => (string) $request->userAgent(),
        'Accept' => '*/*',
    ];
    if ($request->headers->has('range')) {
        $headers['Range'] = $request->header('range');
    }

    try {
        $upstream = Http::withHeaders($headers)->withOptions([
            'stream' => true,
            'connect_timeout' => 20,
            'timeout' => 0,
        ])->get($url);
    } catch (\Throwable $exception) {
        report($exception);
        return response()->view('download-error', ['message' => 'The media file could not be reached. Please try this quality again.'], 502);
    }

    if (!in_array($upstream->status(), [200, 206], true)) {
        Log::warning('Media download upstream rejected request', ['host' => $host, 'status' => $upstream->status()]);
        return response()->view('download-error', ['message' => 'This download link is no longer available. Please analyze the media link again.'], 502);
    }

    AnalyticsEvent::recordDownload($request, $download['meta'] ?? []);
    $psrResponse = $upstream->toPsrResponse();
    $stream = $psrResponse->getBody();
    $responseHeaders = [];
    foreach (['Content-Type', 'Content-Length', 'Content-Range', 'Accept-Ranges', 'Content-Disposition', 'ETag', 'Last-Modified'] as $header) {
        if ($psrResponse->hasHeader($header)) {
            $value = $psrResponse->getHeaderLine($header);
            if ($header === 'Content-Disposition') {
                $value = str_ireplace('vidssave.com', 'save-froms.net', $value);
            }
            $responseHeaders[$header] = $value;
        }
    }
    $responseHeaders['Cache-Control'] = 'private, no-store, max-age=0';
    $responseHeaders['X-Content-Type-Options'] = 'nosniff';

    return response()->stream(function () use ($stream) {
        @set_time_limit(0);
        while (!$stream->eof()) {
            echo $stream->read(1024 * 1024);
            if (ob_get_level() > 0) {
                @ob_flush();
            }
            flush();
        }
        $stream->close();
    }, $upstream->status(), $responseHeaders);
})->middleware('throttle:20,1')->withoutMiddleware([
    \App\Http\Middleware\EncryptCookies::class,
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    \App\Http\Middleware\VerifyCsrfToken::class,
])->name('download.file');

Route::get('/prepare-download/{token}', function (Request $request, string $token) {
    $cached = Cache::get('vidssave_prepare:'.$token);
    if (!$cached) {
        return response()->view('download-error', ['message' => 'This download request has expired. Please return to the downloader and try the quality again.'], 410);
    }
    $resource = is_array($cached) ? ($cached['resource'] ?? null) : $cached;
    $meta = is_array($cached) ? ($cached['meta'] ?? []) : [];
    $response = Http::timeout(120)->get('https://plugin.vidssave.com/api/sse', ['request' => $resource]);
    $event = null;
    $lines = preg_split('/\r\n|\r|\n/', $response->body());
    for ($i = 0; $i < count($lines); $i++) {
        if (trim($lines[$i]) === 'event: success' && isset($lines[$i + 1])) {
            $json = trim(preg_replace('/^data:\s*/', '', $lines[$i + 1]));
            $event = json_decode($json, true);
            break;
        }
    }
    $download = $event['download_link'] ?? null;
    if (!$response->successful() || !filter_var($download, FILTER_VALIDATE_URL)) {
        Cache::forget('vidssave_prepare:'.$token);
        Log::warning('Vidssave could not prepare resource', ['status' => $response->status(), 'event' => $event]);
        return response()->view('download-error', ['message' => 'This quality is temporarily unavailable. Please choose another quality or try again with a different public link.'], 502);
    }
    Cache::forget('vidssave_prepare:'.$token);
    $directToken = Str::random(40);
    Cache::put('direct_download:'.$directToken, ['url' => $download, 'meta' => $meta], now()->addMinutes(20));
    return redirect()->route('download.file', $directToken);
})->middleware('throttle:10,1')->name('download.prepare');

Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/faq', function () {
    return view('faq', ['faqs' => Faq::active()->orderBy('sort_order')->orderBy('id')->get()]);
})->name('faq');
Route::get('/supported-sites', function () {
    return view('supported-sites', [
        'platforms' => SupportedSite::active()->orderBy('sort_order')->orderBy('name')->get(),
        'guides' => LandingPage::active()->orderBy('sort_order')->orderBy('title')->get(),
    ]);
})->name('supported-sites');
Route::get('/about', fn () => view('static-page', ['title' => 'About Save-Froms']))->name('about');
Route::get('/contact', fn () => view('static-page', ['title' => 'Contact Us']))->name('contact');
Route::get('/privacy-policy', fn () => view('static-page', [
    'title' => 'Privacy Policy',
    'content' => '<h2>Analytics and download activity</h2><p>Save-Froms records limited technical information such as IP address, browser user agent, visited page, referrer, download platform, format, quality, and event time. This information is used for website analytics, security, abuse prevention, and service improvement.</p><p>Analytics records are available only inside the protected administrator area. Save-Froms does not store the downloaded media file itself. You should only download public content that you own or have permission to use.</p>',
]))->name('privacy');
Route::get('/terms-of-service', fn () => view('static-page', ['title' => 'Terms of Service']))->name('terms');

Route::get('/robots.txt', function () {
    return response("User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /download-processing/\nSitemap: ".url('/sitemap.xml'), 200)
        ->header('Content-Type', 'text/plain');
});

Route::get('/sitemap.xml', function () {
    return response(app(SitemapGenerator::class)->xml(), 200, [
        'Content-Type' => 'application/xml; charset=UTF-8',
        'Cache-Control' => 'public, max-age=3600',
    ]);
})->withoutMiddleware([
    \App\Http\Middleware\EncryptCookies::class,
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    \App\Http\Middleware\VerifyCsrfToken::class,
]);

Route::get('/{slug}', function (string $slug) {
    $site = SupportedSite::active()->where('slug', $slug)->first();
    if ($site) {
        return view('platform', [
            'site' => $site,
            'name' => $site->name,
            'title' => $site->meta_title ?: $site->name.' Downloader',
            'description' => $site->meta_description ?: $site->description,
        ]);
    }

    $page = LandingPage::active()->where('slug', $slug)->firstOrFail();
    return view('landing-page', [
        'page' => $page,
        'relatedPages' => LandingPage::active()->where('id', '<>', $page->id)->orderBy('sort_order')->limit(6)->get(),
    ]);
})->where('slug', '[a-z0-9-]+');

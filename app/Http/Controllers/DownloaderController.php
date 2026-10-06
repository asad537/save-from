<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\SupportedSite;
use App\Models\LandingPage;

class DownloaderController extends Controller
{
    public function analyze(Request $request)
    {
        $request->validate(['url' => ['required', 'url', 'max:2048']], ['url.url' => 'Please enter a valid public URL.']);
        $returnSlug = Str::slug((string) $request->input('return_slug'));
        $returnPage = $returnSlug ? LandingPage::active()->where('slug', $returnSlug)->first() : null;
        $host = preg_replace('/^www\./', '', strtolower(parse_url($request->url, PHP_URL_HOST) ?: ''));
        $site = SupportedSite::active()->get()->first(function ($site) use ($host) {
            return $site->domainList()->contains(function ($domain) use ($host) {
                return $host === $domain || Str::endsWith($host, '.'.$domain);
            });
        });
        if (!$site) return back()->withInput()->withErrors(['url' => 'This platform is not supported or is currently disabled.']);
        $match = ['name' => $site->name, 'slug' => $site->slug];
        try {
            $response = Http::asJson()->timeout(25)->retry(1, 500)->post(config('services.vidssave.plugin_endpoint'), [
                'url' => '/media/parse',
                'data' => [
                    'origin' => 'source',
                    'link' => $request->url,
                    // This legacy spelling is required by the Chrome extension provider.
                    'plugin_token' => base64_encode('vidssave_brower_plugin_'.round(microtime(true) * 1000)),
                ],
                'token' => '',
            ]);

            $payload = $response->json();
            if (isset($payload['data']['data']) && is_array($payload['data']['data'])) {
                $payload = $payload['data'];
            }
            $data = is_array($payload) && isset($payload['data']) && is_array($payload['data']) ? $payload['data'] : $payload;
            $resources = [];
            foreach (['cache', 'source'] as $origin) {
                if (!empty($data[$origin]['resources']) && is_array($data[$origin]['resources'])) {
                    $resources = array_merge($resources, $data[$origin]['resources']);
                }
            }
            if (!empty($data['resources']) && is_array($data['resources'])) {
                $resources = $data['resources'];
            }
            if (!$response->successful() || !$resources) {
                throw new \RuntimeException('Provider returned no media resources.');
            }

            $mediaTitle = $data['title'] ?? $match['name'].' media';
            $formats = collect($resources)->filter(function ($resource) {
                return is_array($resource);
            })->map(function ($resource) use ($match, $mediaTitle) {
                $type = strtolower((string) ($resource['type'] ?? 'video'));
                $audio = Str::contains($type, ['audio', 'music', 'mp3']);
                $bytes = (int) ($resource['size'] ?? $resource['filesize'] ?? 0);
                $downloadUrl = $resource['download_url'] ?? $resource['downloadUrl'] ?? $resource['download'] ?? $resource['url'] ?? $resource['link'] ?? null;
                $actualFormat = strtoupper((string) ($resource['original_format'] ?? $resource['format'] ?? $resource['ext'] ?? ($audio ? 'MP3' : 'MP4')));
                $actualFormat = preg_replace('/[^A-Z0-9]/', '', $actualFormat);
                $quality = collect([
                    $resource['quality'] ?? null,
                    $resource['resolution'] ?? null,
                    $resource['label'] ?? null,
                ])->first(function ($value) {
                    return is_scalar($value) && trim((string) $value) !== '';
                }) ?: ($audio ? 'Audio' : 'Original quality');
                $prepareToken = null;
                $trackedDownloadUrl = null;
                $meta = [
                    'platform' => $match['name'],
                    'title' => $mediaTitle,
                    'format' => $audio ? 'MP3' : ($actualFormat ?: 'MP4'),
                    'quality' => $quality,
                ];
                if ($downloadUrl) {
                    $directToken = Str::random(40);
                    Cache::put('direct_download:'.$directToken, ['url' => $downloadUrl, 'meta' => $meta], now()->addMinutes(20));
                    $trackedDownloadUrl = route('download.file', $directToken);
                }
                if (!$downloadUrl && !empty($resource['resource_content'])) {
                    $prepareToken = Str::random(40);
                    Cache::put('vidssave_prepare:'.$prepareToken, ['resource' => $resource['resource_content'], 'meta' => $meta], now()->addMinutes(20));
                }
                return [
                    'type' => $audio ? 'Audio' : 'Video',
                    'quality' => $quality,
                    'format' => $audio ? 'MP3' : ($actualFormat ?: 'MP4'),
                    'size' => $bytes >= 1048576 ? number_format($bytes / 1048576, 2).' MB' : ($bytes >= 1024 ? number_format($bytes / 1024, 2).' KB' : ($bytes ? $bytes.' B' : 'Size varies')),
                    'download_url' => $trackedDownloadUrl,
                    'prepare_token' => $prepareToken,
                ];
            })->filter(fn ($format) => $format['quality'] && $format['format'])->unique(fn ($format) => $format['type'].'|'.$format['quality'].'|'.$format['format'])->values()->all();

            if (!$formats) throw new \RuntimeException('No downloadable formats found.');
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->withErrors(['url' => 'The media provider could not process this link right now. Please try again.']);
        }

        $destination = $returnPage ? url('/'.$returnPage->slug) : route('home');

        return redirect()->to($destination)->withInput()->with('download_result', [
            'url' => $request->url,
            'platform' => $match,
            'formats' => $formats,
            'title' => $mediaTitle,
            'thumbnail' => $data['thumbnail'] ?? null,
            'duration' => $this->formatDuration($data['duration'] ?? 0),
        ]);
    }

    private function formatDuration($seconds)
    {
        $seconds = (int) $seconds;
        return $seconds > 0 ? sprintf('%02d:%02d', floor($seconds / 60), $seconds % 60) : null;
    }
}

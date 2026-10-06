<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DownloadProxyTest extends TestCase
{
    use RefreshDatabase;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');
    }

    public function test_it_streams_a_provider_download_through_the_application()
    {
        Http::fake([
            'https://down-de.vidssave.com/*' => Http::response('video-bytes', 200, [
                'Content-Type' => 'video/mp4',
                'Content-Length' => '11',
                'Accept-Ranges' => 'bytes',
                'Content-Disposition' => 'attachment; filename="vidssave.com sample.mp4"',
            ]),
        ]);

        Cache::put('direct_download:test-token', [
            'url' => 'https://down-de.vidssave.com/download/sample.mp4',
            'meta' => ['platform' => 'YouTube', 'format' => 'MP4', 'quality' => '720P'],
        ], now()->addMinutes(20));

        $response = $this->get('/download-file/test-token');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'video/mp4');
        $response->assertHeader('Content-Disposition', 'attachment; filename="save-froms.net sample.mp4"');
        $this->assertSame('video-bytes', $response->streamedContent());
        $this->assertDatabaseHas('analytics_events', [
            'event_type' => 'download',
            'platform' => 'YouTube',
            'format' => 'MP4',
            'quality' => '720P',
        ]);
    }

    public function test_it_rejects_an_untrusted_download_host()
    {
        Http::fake();
        Cache::put('direct_download:bad-token', [
            'url' => 'https://example.com/file.mp4',
            'meta' => [],
        ], now()->addMinutes(20));

        $this->get('/download-file/bad-token')->assertStatus(502);
        Http::assertNothingSent();
    }
}

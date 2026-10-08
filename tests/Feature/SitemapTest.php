<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_is_generated_from_current_published_posts()
    {
        BlogPost::create([
            'title' => 'Current sitemap guide',
            'slug' => 'current-sitemap-guide',
            'content' => '<p>Current sitemap content.</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('http://localhost/blog/current-sitemap-guide', false);

        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));
    }
}

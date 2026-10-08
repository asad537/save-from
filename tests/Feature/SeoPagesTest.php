<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\LandingPage;
use App\Models\SupportedSite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');
    }

    public function test_static_pages_have_real_content_and_no_placeholder()
    {
        foreach (['/about', '/contact', '/privacy-policy', '/terms-of-service'] as $path) {
            $this->get($path)
                ->assertOk()
                ->assertDontSee('ready for your final content')
                ->assertSee('<h1>', false);
        }

        $this->get('/contact')->assertSee(config('app.support_email'))->assertSee(config('app.legal_email'));
        $this->get('/privacy')->assertRedirect('/privacy-policy');
        $this->get('/terms')->assertRedirect('/terms-of-service');
    }

    public function test_header_no_longer_advertises_an_extension()
    {
        $this->get('/')->assertOk()->assertDontSee('Download Extension')->assertSee('Free Online <em>Video Downloader</em>', false);
    }

    public function test_platform_page_uses_keyword_heading_and_links_to_its_cluster()
    {
        $youtube = SupportedSite::where('slug', 'youtube-downloader')->first();
        $this->assertNotNull($youtube);
        $this->assertSame('[YouTube] Video Downloader', $youtube->headline());

        $response = $this->get('/youtube-downloader');
        $response->assertOk()
            ->assertSee('<h1><em>YouTube</em> Video Downloader</h1>', false)
            ->assertSee('/youtube-shorts-downloader')
            ->assertSee('/youtube-to-mp4')
            ->assertSee('/youtube-mp3-downloader')
            ->assertSee('/download-troubleshooting')
            ->assertSee('"@type":"BreadcrumbList"', false);

        $this->get('/twitter-downloader')->assertOk()->assertSee('<h1><em>Twitter / X</em> Video Downloader</h1>', false);
        $this->get('/twitch-downloader')->assertOk()->assertSee('<h1><em>Twitch</em> Clip Downloader</h1>', false);
    }

    public function test_landing_page_links_to_parent_platform_and_siblings()
    {
        $page = LandingPage::where('slug', 'youtube-to-mp4')->first();
        $this->assertSame('youtube-downloader', $page->parentPlatform()->slug);
        $this->assertEqualsCanonicalizing(['youtube-shorts-downloader', 'youtube-mp3-downloader'], $page->siblingPages()->pluck('slug')->all());

        $this->get('/youtube-to-mp4')->assertOk()->assertSee('related-card is-parent', false)->assertSee('/youtube-downloader');
    }

    public function test_supported_sites_hub_shows_capability_table()
    {
        $this->get('/supported-sites')->assertOk()
            ->assertSee('<h1>Supported Video Download Sites</h1>', false)
            ->assertSee('cap-table', false)
            ->assertSee('Twitch Clip Downloader');
    }

    public function test_blog_article_cta_targets_most_specific_action_page()
    {
        $this->get('/blog/download-youtube-shorts-on-mobile')->assertOk()->assertSee('href="'.url('/youtube-shorts-downloader').'"><i class="bi bi-download"></i>&nbsp; Open the YouTube Shorts Downloader', false);
        $this->get('/blog/facebook-video-links-public-posts-watch-privacy')->assertOk()->assertSee('Open the Facebook Video Downloader');

        BlogPost::create(['title' => 'Generic', 'slug' => 'generic-guide', 'content' => '<p>x</p>', 'status' => 'published', 'published_at' => now()]);
        $this->get('/blog/generic-guide')->assertOk()->assertSee('Open the downloader');
    }

    public function test_faq_is_site_level_only()
    {
        $this->get('/faq')->assertOk()
            ->assertSee('How long does a download link stay valid?')
            ->assertDontSee('How do I download a video with Save-Froms?');
    }

    public function test_blog_index_uses_the_site_pagination_component()
    {
        $this->get('/blog')->assertOk()
            ->assertSee('blog-pagination', false)
            ->assertSee('Showing 1 to 12 of 16 guides')
            ->assertDontSee('<svg', false);
    }
}

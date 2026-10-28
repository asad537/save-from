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
            ->assertSee('<h1>Video Downloaders for Supported Sites</h1>', false)
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

    public function test_author_page_lists_articles_and_is_linked_from_posts()
    {
        $this->get('/authors/save-froms-editorial-team')->assertRedirect('/authors/alex-roy');
        $this->get('/authors/alex-roy')->assertOk()
            ->assertSee('<h1 style="margin:10px 0 0">Alex Roy</h1>', false)->assertSee('"@type":"Person"', false)->assertSee('pen name')
            ->assertSee('Download YouTube Shorts on Mobile')
            ->assertSee('"@type":"ProfilePage"', false);

        $this->get('/blog/download-youtube-shorts-on-mobile')->assertSee(route('author'));
        $this->get('/sitemap.xml')->assertSee(route('author'), false);
    }

    public function test_landing_pages_show_hierarchical_breadcrumbs()
    {
        $this->get('/youtube-to-mp4')->assertOk()
            ->assertSee('landing-breadcrumb', false)
            ->assertSee('"name":"YouTube","item":"'.url('/youtube-downloader').'"', false)
            ->assertSee('MP4 vs WEBM: which should you download?');

        $this->get('/youtube-mp3-downloader')->assertOk()->assertSee('<h1>YouTube to <em>MP3</em> Downloader</h1>', false);
        $this->get('/youtube-shorts-downloader')->assertOk()->assertSee('How to download a YouTube Short on iPhone');
    }

    public function test_duplicate_tool_pages_redirect_to_their_platform_hub()
    {
        $this->get('/download-vimeo-videos')->assertRedirect('/vimeo-downloader');
        $this->get('/dailymotion-to-mp4')->assertRedirect('/dailymotion-downloader');
        $this->get('/youtube-link-downloader')->assertRedirect('/youtube-downloader');

        $this->assertFalse(LandingPage::active()->whereIn('slug', ['download-vimeo-videos', 'dailymotion-to-mp4', 'youtube-link-downloader'])->exists());
        $this->get('/sitemap.xml')->assertDontSee('/download-vimeo-videos</loc>', false)->assertDontSee('/dailymotion-to-mp4</loc>', false)->assertDontSee('/youtube-link-downloader</loc>', false);
        $this->get('/blog/download-video-web-page-find-public-source-link-guide')->assertOk()->assertDontSee('/youtube-link-downloader')->assertSee('YouTube Video Downloader');
    }

    public function test_tool_pages_use_exact_match_headings_and_short_titles()
    {
        $this->get('/vimeo-downloader')->assertOk()->assertSee('<h1><em>Vimeo</em> Video Downloader</h1>', false)->assertSee('<title>Vimeo Video Downloader – Save Vimeo Videos as MP4 | Save-Froms</title>', false);
        $this->get('/dailymotion-downloader')->assertOk()->assertSee('<h1><em>Dailymotion</em> Video Downloader</h1>', false);
        $this->get('/youtube-shorts-downloader')->assertOk()->assertSee('<h1><em>YouTube Shorts</em> Downloader</h1>', false);
        $this->get('/tiktok-mp4-downloader')->assertOk()->assertSee('<h1><em>TikTok MP4</em> Downloader</h1>', false);
        $this->get('/instagram-stories-downloader')->assertOk()->assertSee('<h1><em>Instagram Stories</em> Downloader</h1>', false);

        foreach (SupportedSite::active()->get() as $site) {
            $this->assertLessThanOrEqual(62, mb_strlen($site->meta_title), $site->slug.' title too long');
        }
        foreach (LandingPage::active()->get() as $page) {
            $this->assertLessThanOrEqual(62, mb_strlen($page->meta_title ?: $page->title), $page->slug.' title too long');
        }
    }

    public function test_pages_declare_their_real_language()
    {
        $this->get('/blog/baixar-video-instagram-links-publicos-guia')->assertOk()
            ->assertSee('<html lang="pt" dir="ltr">', false)
            ->assertSee('<meta property="og:locale" content="pt_BR">', false);
        $this->get('/blog/ar-youtube-video-download-mp4-quality-guide')->assertOk()
            ->assertSee('<html lang="ar" dir="rtl">', false)
            ->assertSee('<meta property="og:locale" content="ar_AR">', false);
        $this->get('/youtube-downloader')->assertSee('<html lang="en" dir="ltr">', false)->assertSee('content="en_US"', false);
    }

    public function test_index_php_redirects_to_the_clean_url()
    {
        $this->get('/index.php')->assertRedirect('/');
        $this->get('/index.php/youtube-downloader')->assertRedirect('/youtube-downloader');
        $this->get('/index.php?utm_source=x')->assertStatus(301)->assertHeader('Location', 'http://localhost/?utm_source=x');
    }

    public function test_uk_keyword_guides_render_with_a_matching_tool_cta()
    {
        $expected = [
            'instagram-story-downloader-story-saver-public-stories-guide' => '/instagram-stories-downloader',
            'mp4-to-mp3-converter-or-audio-download-guide' => '#downloader',
            'download-tiktok-videos-without-watermark-public-link-guide' => '/tiktok-downloader',
            'youtube-4k-video-download-mp4-2160p-quality-guide' => '/youtube-to-mp4',
            'download-youtube-clips-clip-link-vs-watch-link-guide' => '/youtube-downloader',
        ];
        foreach ($expected as $slug => $cta) {
            $post = BlogPost::published()->where('slug', $slug)->first();
            $this->assertNotNull($post, $slug);
            $this->assertGreaterThan(800, str_word_count(strip_tags($post->content)), $slug.' is thin');
            $this->get('/blog/'.$slug)->assertOk()
                ->assertSee('<h1>'.e($post->title).'</h1>', false)
                ->assertSee('"@type":"BlogPosting"', false)
                ->assertSee($cta.'"><i class="bi bi-download"></i>', false);
            $this->assertFileExists(public_path($post->featured_image));
        }
        $this->get('/sitemap.xml')->assertSee('/blog/download-youtube-clips-clip-link-vs-watch-link-guide</loc>', false);
    }

    public function test_thin_facebook_reels_post_is_merged_and_savefrom_search_is_expanded()
    {
        $this->get('/blog/download-facebook-reels-android-iphone-files-guide')->assertRedirect('/blog/facebook-reel-downloader-public-reels-mobile-guide');
        $this->get('/sitemap.xml')->assertDontSee('download-facebook-reels-android-iphone-files-guide');

        $merged = BlogPost::published()->where('slug', 'facebook-reel-downloader-public-reels-mobile-guide')->first();
        $this->assertGreaterThan(1150, str_word_count(strip_tags($merged->content)));
        $this->get('/blog/'.$merged->slug)->assertOk()->assertSee('Android and iPhone at a glance')->assertSee('/facebook-reels-downloader');

        $savefrom = BlogPost::published()->where('slug', 'savefrom-search-public-link-guide')->first();
        $this->assertGreaterThan(1350, str_word_count(strip_tags($savefrom->content)));
        $this->get('/blog/'.$savefrom->slug)->assertOk()->assertSee('How to tell a safe downloader from a risky one')->assertSee('<title>SaveFrom Search: What People Want and How to Get It Safely</title>', false);
    }

    public function test_homepage_targets_video_downloader_and_lists_english_guides_only()
    {
        $response = $this->get('/');
        $response->assertOk()
            ->assertSee('<title>Video Downloader Online – Free MP4 &amp; MP3 | Save-Froms</title>', false)
            ->assertSee('Which video downloader page should you use?')
            ->assertSee('/twitch-downloader')
            ->assertSee('/instagram-stories-downloader')
            ->assertDontSee('watch?v=abc123')
            ->assertSee('Video download guides');

        $arabic = BlogPost::published()->where('language', 'ar')->first();
        if ($arabic) {
            $response->assertDontSee(e($arabic->title), false);
        }
        $this->assertGreaterThan(850, str_word_count(strip_tags(\App\Models\HomepageContent::first()->content)));
    }

    public function test_tool_pages_are_no_longer_thin_and_youtube_pages_show_a_real_result()
    {
        foreach (['youtube-mp3-downloader', 'instagram-stories-downloader', 'tiktok-mp3-downloader', 'tiktok-mp4-downloader', 'facebook-reels-downloader', 'instagram-reels-downloader'] as $slug) {
            $page = LandingPage::active()->where('slug', $slug)->first();
            $this->assertNotNull($page, $slug);
            $this->assertGreaterThan(900, str_word_count(strip_tags($page->content)), $slug.' is thin');
            $this->assertGreaterThanOrEqual(4, count($page->faq_items), $slug.' lacks FAQs');
            $this->get('/'.$slug)->assertOk()->assertSee('<section class="landing-faq">', false);
        }

        $this->assertFileExists(public_path('images/screenshots/youtube-result-big-buck-bunny.jpg'));
        $this->get('/youtube-downloader')->assertOk()->assertSee('images/screenshots/youtube-result-big-buck-bunny.jpg')->assertSee('result-figure', false);
        $this->get('/youtube-to-mp4')->assertOk()->assertSee('images/screenshots/youtube-result-big-buck-bunny.jpg');
        $this->get('/tiktok-downloader')->assertOk()->assertSee('Photo-mode posts and slideshows');
    }

    public function test_october_six_posts_are_merged_and_twitch_x_expanded()
    {
        $merges = [
            'dailymotion-video-url-guide-short-links-quality' => 'dailymotion-video-downloader-public-links-quality-guide',
            'vimeo-download-guide-public-links-privacy-quality' => 'download-vimeo-videos-public-links-passwords-privacy-guide',
            'tiktok-link-guide-mp4-mp3-short-urls' => 'tiktok-mp4-vs-mp3-download-format-guide',
            'instagram-reels-posts-stories-public-link-guide' => 'instagram-reels-download-quality-links-mobile-tips',
        ];
        foreach ($merges as $old => $new) {
            $this->get('/blog/'.$old)->assertRedirect('/blog/'.$new);
            $target = BlogPost::published()->where('slug', $new)->first();
            $this->assertNotNull($target, $new);
            $this->assertGreaterThan(850, str_word_count(strip_tags($target->content)), $new.' is thin');
            $this->assertStringNotContainsString('/blog/'.$old, $target->content);
            $this->get('/blog/'.$new)->assertOk();
        }
        $this->get('/sitemap.xml')->assertDontSee('dailymotion-video-url-guide-short-links-quality');

        foreach (['twitch-clips-vods-live-streams-public-link-guide', 'save-video-public-x-post-correct-url'] as $slug) {
            $post = BlogPost::published()->where('slug', $slug)->first();
            $this->assertGreaterThan(850, str_word_count(strip_tags($post->content)), $slug.' is thin');
            $this->get('/blog/'.$slug)->assertOk()->assertSee('URL anatomy');
        }
    }

    public function test_threads_platform_page_is_live_and_pinterest_stays_off_until_prepare_works()
    {
        $site = SupportedSite::active()->where('slug', 'threads-downloader')->first();
        $this->assertNotNull($site);
        $this->assertGreaterThan(850, str_word_count(strip_tags($site->description)));
        $this->assertTrue($site->domainList()->contains('threads.com'));
        $this->get('/threads-downloader')->assertOk()->assertSee('<h1><em>Threads</em> Video Downloader</h1>', false)->assertSee('Threads link anatomy');
        $this->get('/supported-sites')->assertOk()->assertSee('/threads-downloader')->assertDontSee('/pinterest-downloader');
        $this->get('/')->assertSee('/threads-downloader');
        $this->get('/sitemap.xml')->assertSee('/threads-downloader</loc>', false)->assertDontSee('/pinterest-downloader</loc>', false);

        // Pinterest content is ready but the provider cannot prepare Pin downloads yet.
        $pinterest = SupportedSite::where('slug', 'pinterest-downloader')->first();
        $this->assertFalse($pinterest->is_active);
        $this->assertGreaterThan(850, str_word_count(strip_tags($pinterest->description)));
        $this->get('/pinterest-downloader')->assertNotFound();
        $this->assertNull(SupportedSite::active()->where('slug', 'reddit-downloader')->first());
    }

    public function test_public_pages_are_served_from_cache_and_assets_are_local()
    {
        $first = $this->get('/youtube-downloader');
        $first->assertOk()->assertHeader('X-Page-Cache', 'MISS');
        $this->get('/youtube-downloader')->assertOk()->assertHeader('X-Page-Cache', 'HIT')->assertSee('<h1><em>YouTube</em> Video Downloader</h1>', false);

        // Pages that render session state are never cached.
        $this->get('/admin/login')->assertHeaderMissing('X-Page-Cache');
        $this->get('/sitemap.xml')->assertHeaderMissing('X-Page-Cache');
        $this->get('/youtube-downloader?utm_source=x')->assertHeaderMissing('X-Page-Cache');
        $this->withSession(['download_result' => ['url' => 'x', 'platform' => ['name' => 'YouTube', 'slug' => 'youtube-downloader'], 'formats' => [], 'title' => 't', 'thumbnail' => null, 'duration' => '']])
            ->get('/')->assertOk()->assertHeaderMissing('X-Page-Cache');

        // The downloader form works without a session-bound CSRF token.
        $this->post('/analyze', ['url' => 'not a url'])->assertStatus(302)->assertSessionHasErrors('url');

        $home = $this->get('/');
        $home->assertDontSee('fonts.googleapis.com')->assertDontSee('cdn.jsdelivr.net')
            ->assertSee('css/fonts.css')->assertSee('css/icons.css')->assertSee('fonts/dm-sans-400-latin.woff2');
        foreach (['fonts/dm-sans-400-latin.woff2', 'fonts/space-grotesk-500-latin.woff2', 'css/fonts.css', 'css/icons.css'] as $asset) {
            $this->assertFileExists(public_path($asset));
        }
        $icons = file_get_contents(public_path('css/icons.css'));
        foreach (['download', 'youtube', 'threads', 'chevron-right', 'list', 'x-lg'] as $icon) {
            $this->assertStringContainsString('.bi-'.$icon.'{', $icons);
        }
    }

    public function test_contextual_internal_links_and_tool_page_guides()
    {
        $this->get('/youtube-to-mp4')->assertOk()
            ->assertSee('YouTube to MP4 Downloader guides from the blog')
            ->assertSee('/blog/youtube-4k-video-download-mp4-2160p-quality-guide')
            ->assertSee('/blog/download-youtube-clips-clip-link-vs-watch-link-guide');
        $this->get('/instagram-stories-downloader')->assertOk()->assertSee('/blog/instagram-story-downloader-story-saver-public-stories-guide');
        $this->get('/tiktok-mp4-downloader')->assertOk()->assertSee('/blog/download-tiktok-videos-without-watermark-public-link-guide');
        $this->get('/youtube-mp3-downloader')->assertOk()->assertSee('/blog/mp4-to-mp3-converter-or-audio-download-guide');

        $this->get('/tiktok-downloader')->assertOk()->assertSee('<a href="/blog/download-tiktok-videos-without-watermark-public-link-guide">watermark</a>', false);
        $this->get('/blog/youtube-video-download-from-youtube-public-link-guide')->assertOk()->assertSee('<a href="/youtube-shorts-downloader">Shorts</a>', false);
        $this->get('/blog/instagram-video-downloader-public-links-formats-guide')->assertOk()->assertSee('<a href="/instagram-stories-downloader">Stories</a>', false);

        $linked = BlogPost::published()->where('language', 'en')->get()->filter(function ($post) {
            return substr_count($post->content, 'href="/') >= 2;
        })->count();
        $this->assertGreaterThanOrEqual(40, $linked, 'most posts should carry at least two internal links');
    }

    public function test_on_site_batch_hubs_pagination_topics_404_and_robots()
    {
        foreach (['download-troubleshooting', 'how-to-download-videos'] as $slug) {
            $page = LandingPage::active()->where('slug', $slug)->first();
            $this->assertGreaterThan(900, str_word_count(strip_tags($page->content)), $slug.' is thin');
            $this->assertCount(6, $page->faq_items);
        }

        $this->get('/supported-sites')->assertOk()
            ->assertSee('<title>Supported Sites – Every Video Downloader We Offer | Save-Froms</title>', false)
            ->assertSee('Which downloader should you start with?')->assertSee('Supported sites FAQ');

        $this->get('/blog')->assertOk()->assertSee('<h1>Video Download Guides</h1>', false)
            ->assertSee('<link rel="canonical" href="'.route('blog').'">', false)->assertSee('/guides/youtube');
        $this->get('/blog?page=2')->assertOk()->assertSee('Video Download Guides – Page 2')
            ->assertSee('<link rel="canonical" href="'.route('blog').'?page=2">', false);
        $this->get('/blog?page=99')->assertNotFound();

        $this->get('/guides/youtube')->assertOk()->assertSee('<h1>YouTube Download Guides</h1>', false)
            ->assertSee('/blog/youtube-4k-video-download-mp4-2160p-quality-guide')->assertSee('"@type":"CollectionPage"', false)
            ->assertDontSee('/blog/ar-youtube-video-download-mp4-quality-guide');
        $this->get('/guides/formats-and-quality')->assertOk()->assertSee('/blog/mp4-to-mp3-converter-or-audio-download-guide');
        $this->get('/guides/nope')->assertNotFound();
        $this->get('/youtube-downloader')->assertSee('/guides/youtube');
        $this->get('/sitemap.xml')->assertSee('/guides/tiktok</loc>', false);

        $this->get('/no-such-page-xyz')->assertNotFound()->assertSee('That page is not here')->assertSee('/youtube-downloader')
            ->assertSee('<meta name="robots" content="noindex,follow">', false);

        $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /prepare-download/')->assertDontSee('download-processing');
        $this->assertFileDoesNotExist(public_path('sitemap.xml'));
    }

    public function test_keyword_of_the_day_mp4_guide_and_instagram_photo_page()
    {
        $post = BlogPost::published()->where('slug', 'mp4-downloader-save-any-public-video-as-mp4-guide')->first();
        $this->assertNotNull($post);
        $this->assertGreaterThan(900, str_word_count(strip_tags($post->content)));
        $this->get('/blog/'.$post->slug)->assertOk()->assertSee('<h1>'.e($post->title).'</h1>', false)->assertSee('/youtube-to-mp4');
        $this->assertFileExists(public_path($post->featured_image));

        $page = LandingPage::active()->where('slug', 'instagram-photo-downloader')->first();
        $this->assertNotNull($page);
        $this->assertGreaterThan(850, str_word_count(strip_tags($page->content)));
        $this->assertCount(6, $page->faq_items);
        $this->get('/instagram-photo-downloader')->assertOk()->assertSee('<h1><em>Instagram Photo</em> Downloader</h1>', false)->assertSee('/instagram-downloader');
        $this->get('/instagram-downloader')->assertOk()->assertSee('/instagram-photo-downloader');
        $this->get('/sitemap.xml')->assertSee('/instagram-photo-downloader</loc>', false);
        $this->assertStringContainsString('.bi-image{', file_get_contents(public_path('css/icons.css')));

        $this->withSession(['download_result' => ['url' => 'x', 'platform' => ['name' => 'Instagram', 'slug' => 'instagram-downloader'], 'formats' => [['type' => 'Image', 'quality' => '1 - 1440X960', 'format' => 'JPG', 'size' => 'Size varies', 'download_url' => 'https://save-froms.net/download-file/abc', 'prepare_token' => null]], 'title' => 'Photo post', 'thumbnail' => null, 'duration' => '']])
            ->get('/instagram-photo-downloader')->assertOk()->assertSee('Photos')->assertSee('1 - 1440X960')->assertDontSee('No video formats available');
    }

    public function test_three_more_keyword_guides_are_published()
    {
        $expected = [
            'dl-instagram-videos-photos-reels-stories-link-cheat-sheet' => '/instagram-photo-downloader',
            'free-youtube-download-no-install-no-signup-guide' => '/youtube-downloader',
            'youtube-downloader-app-vs-browser-android-guide' => '/youtube-downloader',
        ];
        foreach ($expected as $slug => $link) {
            $post = BlogPost::published()->where('slug', $slug)->first();
            $this->assertNotNull($post, $slug);
            $this->assertGreaterThan(900, str_word_count(strip_tags($post->content)), $slug.' is thin');
            $this->assertLessThanOrEqual(160, mb_strlen($post->meta_description));
            $this->get('/blog/'.$slug)->assertOk()->assertSee('<h1>'.e($post->title).'</h1>', false)->assertSee($link)->assertSee('"@type":"BlogPosting"', false);
            $this->assertFileExists(public_path($post->featured_image));
        }
    }

    public function test_homepage_declares_web_application_schema()
    {
        $this->get('/')->assertSee('"@type":"WebApplication"', false);
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
            ->assertSee('Showing 1 to 12 of')
            ->assertDontSee('<svg', false);
    }
}

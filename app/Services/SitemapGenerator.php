<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\LandingPage;
use App\Models\SupportedSite;

class SitemapGenerator
{
    public function xml(): string
    {
        $entries = collect([
            ['url' => url('/'), 'lastmod' => null, 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['url' => url('/supported-sites'), 'lastmod' => null, 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['url' => url('/faq'), 'lastmod' => null, 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['url' => url('/blog'), 'lastmod' => null, 'changefreq' => 'daily', 'priority' => '0.8'],
            ['url' => url('/about'), 'lastmod' => null, 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['url' => url('/contact'), 'lastmod' => null, 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['url' => url('/privacy-policy'), 'lastmod' => null, 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['url' => url('/terms-of-service'), 'lastmod' => null, 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['url' => route('author'), 'lastmod' => null, 'changefreq' => 'weekly', 'priority' => '0.5'],
        ]);

        SupportedSite::active()->get(['slug', 'updated_at'])->each(function ($site) use ($entries) {
            $entries->push($this->entry(url('/'.$site->slug), $site->updated_at));
        });

        LandingPage::active()->get(['slug', 'updated_at'])->each(function ($page) use ($entries) {
            $entries->push($this->entry(url('/'.$page->slug), $page->updated_at));
        });

        BlogPost::published()->get(['slug', 'updated_at'])->each(function ($post) use ($entries) {
            $entries->push($this->entry(route('blog.show', $post->slug), $post->updated_at));
        });

        $lines = ['<?xml version="1.0" encoding="UTF-8"?>', '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'];
        foreach ($entries as $entry) {
            $lines[] = '  <url>';
            $lines[] = '    <loc>'.$this->escape($entry['url']).'</loc>';
            if ($entry['lastmod']) {
                $lines[] = '    <lastmod>'.$entry['lastmod'].'</lastmod>';
            }
            $lines[] = '    <changefreq>'.$entry['changefreq'].'</changefreq>';
            $lines[] = '    <priority>'.$entry['priority'].'</priority>';
            $lines[] = '  </url>';
        }
        $lines[] = '</urlset>';

        return implode("\n", $lines)."\n";
    }

    public function write(): string
    {
        $path = public_path('sitemap.xml');
        file_put_contents($path, $this->xml(), LOCK_EX);

        return $path;
    }

    private function entry(string $url, $updatedAt): array
    {
        return [
            'url' => $url,
            'lastmod' => $updatedAt ? $updatedAt->toAtomString() : null,
            'changefreq' => 'weekly',
            'priority' => '0.8',
        ];
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}

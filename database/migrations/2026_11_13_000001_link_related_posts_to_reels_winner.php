<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Internal-linking boost for the Instagram Reels page (2026-11-13).
 *
 * GSC showed only 2 internal links pointing to the Reels winner
 * (instagram-reels-download-quality-links-mobile-tips) while 7 closely
 * related Instagram posts did not link it at all. This adds a single
 * natural contextual link from five of those posts (varied anchors) to
 * concentrate internal link equity on the winner and give Google more
 * crawl paths to it. Honest scope: a minor on-page lever — the page's
 * big impression count was a Google-honeymoon spike that has since faded,
 * and only authority/backlinks will lift it back on merit. Guarded so it
 * only inserts where the link is absent; reversible via a marker.
 */
class LinkRelatedPostsToReelsWinner extends Migration
{
    private $winner = '/blog/instagram-reels-download-quality-links-mobile-tips';

    public function up()
    {
        foreach ($this->items() as $slug => $sentence) {
            $p = DB::table('blog_posts')->where('slug', $slug)->first();
            if (! $p || strpos($p->content, $this->winner) !== false) {
                continue;
            }
            $block = '<p data-rel-reels>' . $sentence . '</p>';
            $content = $p->content;
            if (preg_match('/<p><small>/', $content)) {
                $content = preg_replace('/<p><small>/', $block . "\n<p><small>", $content, 1);
            } else {
                $content .= "\n" . $block;
            }
            DB::table('blog_posts')->where('slug', $slug)->update(['content' => $content, 'updated_at' => now()]);
        }
    }

    public function down()
    {
        foreach (array_keys($this->items()) as $slug) {
            $p = DB::table('blog_posts')->where('slug', $slug)->first();
            if (! $p) {
                continue;
            }
            $content = preg_replace('/\n?<p data-rel-reels>.*?<\/p>/s', '', $p->content);
            DB::table('blog_posts')->where('slug', $slug)->update(['content' => $content, 'updated_at' => now()]);
        }
    }

    private function items()
    {
        $w = $this->winner;
        return [
            'instagram-video-downloader-public-links-formats-guide' => 'Specifically after a Reel? See <a href="' . $w . '">how to download Instagram Reels</a> for the exact steps and quality options.',
            'instagram-to-mp4-public-reels-video-quality-guide' => 'For Reels in particular, our <a href="' . $w . '">Instagram Reels download guide</a> walks through which link to copy and the quality that keeps the sound.',
            'save-from-ig-public-reels-posts-guide' => 'To save a Reel on its own, follow our <a href="' . $w . '">guide to downloading Instagram Reels</a>.',
            'dl-instagram-videos-photos-reels-stories-link-cheat-sheet' => 'Reels have their own full walkthrough in our <a href="' . $w . '">Instagram Reels download guide</a>.',
            'snapinsta-alternative-instagram-downloader-safe-guide' => 'Just want Reels? See <a href="' . $w . '">how to download Instagram Reels for free</a>, no app needed.',
        ];
    }
}

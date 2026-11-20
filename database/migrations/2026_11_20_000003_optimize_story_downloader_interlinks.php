<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * On-page optimization for the Instagram story-downloader guide (2026-11-20).
 *
 * This page is one of the few Instagram posts already pulling impressions in
 * GSC, so it is a good hub to concentrate internal links on. It mentions an
 * "anonymous viewer" and "Highlights" in passing but links neither dedicated
 * guide. This adds one natural contextual paragraph linking the two new
 * cluster posts (anonymous story download + Highlights download), giving
 * Google fresh crawl paths into them from an indexed page and tightening the
 * Stories/Highlights topic cluster. Guarded (only inserts if absent) and
 * reversible via the data-rel-stories marker.
 */
class OptimizeStoryDownloaderInterlinks extends Migration
{
    private $slug = 'instagram-story-downloader-story-saver-public-stories-guide';

    private $anon = '/blog/instagram-story-download-anonymously-without-them-knowing-guide';
    private $hl = '/blog/instagram-highlights-download-save-whole-highlight-guide';

    public function up()
    {
        $p = DB::table('blog_posts')->where('slug', $this->slug)->first();
        if (! $p) {
            return;
        }
        if (strpos($p->content, 'data-rel-stories') !== false) {
            return;
        }
        $sentence = 'Related guides: to save a live story without appearing in the viewer list, see <a href="' . $this->anon . '">how to download Instagram stories anonymously</a>; and for the stories a profile chose to keep, our <a href="' . $this->hl . '">download Instagram Highlights guide</a> walks through saving each frame.';
        $block = '<p data-rel-stories>' . $sentence . '</p>';
        $content = $p->content;
        if (preg_match('/<p><small>/', $content)) {
            $content = preg_replace('/<p><small>/', $block . "\n<p><small>", $content, 1);
        } else {
            $content .= "\n" . $block;
        }
        DB::table('blog_posts')->where('slug', $this->slug)->update(['content' => $content, 'updated_at' => now()]);
    }

    public function down()
    {
        $p = DB::table('blog_posts')->where('slug', $this->slug)->first();
        if (! $p) {
            return;
        }
        $content = preg_replace('/\n?<p data-rel-stories>.*?<\/p>/s', '', $p->content);
        DB::table('blog_posts')->where('slug', $this->slug)->update(['content' => $content, 'updated_at' => now()]);
    }
}

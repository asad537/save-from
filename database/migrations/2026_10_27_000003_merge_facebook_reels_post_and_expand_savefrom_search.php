<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Search Console "Crawled - currently not indexed" (2026-10-27):
 * - The 358-word "Download Facebook Reels on Android and iPhone" post duplicated
 *   the Facebook Reel downloader guide. Its unique parts are folded into that
 *   guide, it is unpublished, and BlogController 301s its slug to the guide.
 * - The SaveFrom Search guide gains intent, safety and mobile/desktop sections
 *   so it answers the query rather than restating the generic workflow.
 */
class MergeFacebookReelsPostAndExpandSavefromSearch extends Migration
{
    const THIN = 'download-facebook-reels-android-iphone-files-guide';
    const TARGET = 'facebook-reel-downloader-public-reels-mobile-guide';
    const SAVEFROM = 'savefrom-search-public-link-guide';

    public function up()
    {
        $now = now();

        $target = DB::table('blog_posts')->where('slug', self::TARGET)->first();
        if ($target && strpos($target->content, 'Android and iPhone at a glance') === false) {
            $content = str_replace('<a href="/facebook-downloader">Facebook video downloader</a>', '<a href="/facebook-reels-downloader">Facebook Reels Downloader</a>', $target->content);
            $content = $this->insertBefore($content, '<h2>Why a Facebook Reel download may fail</h2>', $this->reelsSection());
            $content = $this->insertBefore($content, '<p><small>Guide published: October 19, 2026.</small></p>', $this->reelsFaq());
            $content = str_replace('<p><small>Guide published: October 19, 2026.</small></p>', '<p><small>Guide published: October 19, 2026. Updated: October 27, 2026, merging the Android and iPhone file guide.</small></p>', $content);

            DB::table('blog_posts')->where('id', $target->id)->update([
                'content' => $content,
                'excerpt' => 'How to copy a public Facebook Reel link, compare the MP4 quality that comes back, and find the saved file on Android, iPhone or desktop, with fixes for expired links.',
                'meta_description' => 'Download public Facebook Reels on Android, iPhone or desktop: copy the right Reel link, compare the MP4 quality returned, find the saved file and fix expired links.',
                'updated_at' => $now,
            ]);
        }

        DB::table('blog_posts')->where('slug', self::THIN)->update(['status' => 'draft', 'updated_at' => $now]);

        $savefrom = DB::table('blog_posts')->where('slug', self::SAVEFROM)->first();
        if ($savefrom && strpos($savefrom->content, 'What people actually want when they search') === false) {
            $content = $this->insertBefore($savefrom->content, '<h2>How a public-link download workflow works</h2>', $this->savefromIntentSection());
            $content = $this->insertBefore($content, '<h2>Start with the right supported platform page</h2>', $this->savefromSafetySection());
            $content = str_replace('<p><small>Guide updated: October 8, 2026.</small></p>', '<p><small>Guide updated: October 27, 2026.</small></p>', $content);

            DB::table('blog_posts')->where('id', $savefrom->id)->update([
                'content' => $content,
                'meta_title' => 'SaveFrom Search: What People Want and How to Get It Safely',
                'meta_description' => 'Searching for SaveFrom? What the query usually means, how to tell a safe public-link downloader from a fake one, and the mobile and desktop steps that work.',
                'updated_at' => $now,
            ]);
        }
    }

    private function insertBefore(string $content, string $marker, string $section): string
    {
        $pos = strpos($content, $marker);

        return $pos === false ? $content.$section : substr($content, 0, $pos).$section.substr($content, $pos);
    }

    private function reelsSection(): string
    {
        return <<<'HTML'
<h2>Android and iPhone at a glance</h2>
<table>
<thead><tr><th>Device</th><th>Where the file lands</th><th>Move it to your gallery</th><th>If it is missing</th></tr></thead>
<tbody>
<tr><td>Android (Chrome, Samsung Internet)</td><td>Internal storage → Download</td><td>Files by Google → Download → Share or Move to DCIM, or let Google Photos index the folder</td><td>Open the browser's download list; check that storage permission was granted</td></tr>
<tr><td>iPhone or iPad (Safari)</td><td>Files → Downloads (iCloud Drive or On My iPhone)</td><td>Open the video in Files → Share → Save Video</td><td>Tap the arrow in Safari's address bar; check Settings → Safari → Downloads</td></tr>
<tr><td>Desktop (Chrome, Edge, Firefox)</td><td>Downloads folder</td><td>Not needed; play it from Downloads or move it anywhere</td><td>Check the browser's download bar for a blocked or cancelled file</td></tr>
</tbody>
</table>
<p>The browser, not Save-Froms, decides where the file goes. Content blockers, a full device and a "block downloads" setting can all stop a file quietly. If the Reel downloads as a tiny file or will not open, the temporary delivery link had already expired: go back to the original public Reel URL and request a fresh result instead of retrying the old file address.</p>
<p>The in-app Facebook browser is the usual cause of a download that never starts on a phone. Copy the link, close Facebook, and paste it into Chrome or Safari directly.</p>

HTML;
    }

    private function reelsFaq(): string
    {
        return <<<'HTML'
<h3>Why did my Facebook Reel download expire?</h3>
<p>Facebook serves media from temporary addresses. A result page's file links stop working after a short time, so paste the original public Reel URL again for a fresh list.</p>

<h3>Does the download include the Reel's music?</h3>
<p>The file contains whatever audio Facebook returns for that public rendition. Licensed music is sometimes muted in the public version, and no tool can add it back.</p>

HTML;
    }

    private function savefromIntentSection(): string
    {
        return <<<'HTML'
<h2>What people actually want when they search “savefrom”</h2>
<p>The same word covers several different jobs. Matching your job to the right page saves time, because a Reels link, a Shorts link and a Vimeo link each behave differently.</p>
<table>
<thead><tr><th>What you typed</th><th>What you usually need</th><th>Where to start</th></tr></thead>
<tbody>
<tr><td>savefrom youtube, ss youtube, savefrom net youtube</td><td>One YouTube video as MP4, or its audio</td><td><a href="/youtube-downloader">YouTube Video Downloader</a>; for sound only, the <a href="/youtube-mp3-downloader">YouTube to MP3 Downloader</a></td></tr>
<tr><td>savefrom instagram, save from ig, savefrom story</td><td>A public Reel, video post or live Story</td><td><a href="/instagram-reels-downloader">Instagram Reels Downloader</a> or the <a href="/instagram-stories-downloader">Instagram Stories Downloader</a></td></tr>
<tr><td>savefrom tiktok, savefrom without watermark</td><td>A TikTok clip, ideally without the overlay</td><td><a href="/tiktok-downloader">TikTok Video Downloader</a></td></tr>
<tr><td>savefrom facebook, save from fb</td><td>A public Facebook video or Reel</td><td><a href="/facebook-downloader">Facebook Video Downloader</a></td></tr>
<tr><td>savefrom helper, savefrom extension, savefrom apk</td><td>A browser tool that adds a button under videos</td><td>Not needed: a browser page works without installing anything (see the safety section below)</td></tr>
<tr><td>savefrom not working, savefrom 403</td><td>A fix for an error on another site</td><td><a href="/blog/savefrom-not-working-link-preparing-403-fixes">SaveFrom not working: 403, Preparing and link errors</a></td></tr>
</tbody>
</table>
<h2>Check the link before you look for a tool</h2>
<p>Most failed “savefrom” attempts have nothing to do with the website and everything to do with the link. Open the copied address in a private browser window. If it plays without a login, it is public and any honest public-link tool can read it. If it asks you to sign in, join a group or shows “content isn't available”, the media is not public and no tool should be able to fetch it. A link that opens a profile, playlist, feed or search page identifies many items rather than one, so copy the individual post's link instead.</p>

HTML;
    }

    private function savefromSafetySection(): string
    {
        return <<<'HTML'
<h2>How to tell a safe downloader from a risky one</h2>
<p>Because the word is popular, many sites found through a “savefrom” search exist mainly to push installs and adverts. A few checks protect your device and your accounts.</p>
<ul>
<li><strong>No installs.</strong> A browser downloader needs nothing on your device. An APK, a desktop installer or a “helper” extension that asks for permission to read every website is a risk, not a feature. Mobile browsers cannot run extensions anyway.</li>
<li><strong>No logins.</strong> A public-link tool never needs your YouTube, Instagram or Facebook password. A site that asks for one so it can “unlock private videos” is collecting credentials.</li>
<li><strong>One Download button, clearly labelled.</strong> Pages that show several large green buttons, a fake virus warning or a countdown timer are advert traps. The real result should list the format, resolution and estimated size for each file.</li>
<li><strong>The file type matches.</strong> A video download should end in <code>.mp4</code> or <code>.webm</code>, and audio in <code>.mp3</code> or <code>.m4a</code>. An <code>.exe</code>, <code>.apk</code> or <code>.zip</code> is not your video.</li>
<li><strong>HTTPS and a readable privacy page.</strong> Check the padlock, and read what the site keeps. Save-Froms describes its handling in the <a href="/privacy-policy">privacy policy</a>.</li>
</ul>
<h2>Mobile and desktop: the steps that work</h2>
<p>On a phone, copy the link from the platform's own Share menu, switch to Chrome or Safari, paste the link and choose a file. The in-app browser inside Facebook, Instagram or TikTok often blocks downloads, so leave the app first. On a desktop, the address bar is the quickest source for the link, and the browser's Downloads folder is where the file lands. Either way, the result depends on the public link, not on the device, so a clip that returns no file on your phone will return none on a laptop either.</p>

HTML;
    }

    public function down()
    {
        DB::table('blog_posts')->where('slug', self::THIN)->update(['status' => 'published', 'updated_at' => now()]);
        // Merged and expanded content is intentionally retained.
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Duplicate-content pass (2026-10-27): the 6 October short posts overlapped
 * 22-31% with newer, longer posts on the same topic.
 * - Dailymotion, Vimeo, TikTok and Instagram short posts are folded into their
 *   siblings (unique points kept, new page-specific sections added) and 301'd
 *   from BlogController.
 * - Twitch and X have no sibling, so they are expanded in place.
 */
class MergeOctoberSixPostsAndExpandTwitchX extends Migration
{
    const MERGES = [
        'dailymotion-video-url-guide-short-links-quality' => 'dailymotion-video-downloader-public-links-quality-guide',
        'vimeo-download-guide-public-links-privacy-quality' => 'download-vimeo-videos-public-links-passwords-privacy-guide',
        'tiktok-link-guide-mp4-mp3-short-urls' => 'tiktok-mp4-vs-mp3-download-format-guide',
        'instagram-reels-posts-stories-public-link-guide' => 'instagram-reels-download-quality-links-mobile-tips',
    ];

    public function up()
    {
        $now = now();

        foreach (self::MERGES as $old => $new) {
            DB::table('blog_posts')->where('slug', $old)->update(['status' => 'draft', 'updated_at' => $now]);
            foreach (['blog_posts' => 'content', 'supported_sites' => 'description', 'landing_pages' => 'content'] as $table => $column) {
                DB::table($table)->update([$column => DB::raw("REPLACE($column, '/blog/$old', '/blog/$new')")]);
            }
        }

        foreach ($this->targets() as $slug => $t) {
            $row = DB::table('blog_posts')->where('slug', $slug)->first();
            if (!$row || strpos($row->content, $t['marker']) !== false) {
                continue;
            }
            $content = $row->content;
            foreach ($t['replace'] ?? [] as $from => $to) {
                $content = str_replace($from, $to, $content);
            }
            $content = $this->insertBefore($content, $t['before'], $t['section']);
            $update = ['content' => $content, 'updated_at' => $now];
            foreach (['meta_description', 'excerpt', 'featured_image'] as $field) {
                if (!empty($t[$field])) {
                    $update[$field] = $t[$field];
                }
            }
            DB::table('blog_posts')->where('id', $row->id)->update($update);
        }
    }

    private function insertBefore(string $content, string $marker, string $section): string
    {
        $pos = strpos($content, $marker);

        return $pos === false ? $content."\n".$section : substr($content, 0, $pos).$section.substr($content, $pos);
    }

    private function targets()
    {
        return [
            'dailymotion-video-downloader-public-links-quality-guide' => [
                'marker' => 'Dailymotion URL anatomy',
                'before' => '<h2>Frequently asked questions</h2>',
                'replace' => ['The <a href="/blog/dailymotion-video-downloader-public-links-quality-guide">Dailymotion link guide</a> and <a href="/download-troubleshooting">troubleshooting page</a> cover more cases.' => 'The <a href="/download-troubleshooting">troubleshooting page</a> covers more cases.'],
                'featured_image' => 'images/blog/dailymotion-links-quality-guide.jpg',
                'meta_description' => 'How a Dailymotion video downloader handles full links and dai.ly URLs, what the 240p to 1080p renditions mean, embedded players, and why some links return nothing.',
                'section' => <<<'HTML'
<h2>Dailymotion URL anatomy</h2>
<table><thead><tr><th>Address</th><th>What it is</th><th>Use it?</th></tr></thead><tbody>
<tr><td><code>dailymotion.com/video/x8k2p1q</code></td><td>One video; the <code>x</code> code is the video ID</td><td>Yes, the canonical form</td></tr>
<tr><td><code>dai.ly/x8k2p1q</code></td><td>Short link to the same ID</td><td>Yes; it redirects to the form above</td></tr>
<tr><td><code>dailymotion.com/embed/video/x8k2p1q</code></td><td>The player embedded on another site</td><td>Yes; swap <code>/embed/video/</code> for <code>/video/</code> if it fails</td></tr>
<tr><td><code>dailymotion.com/playlist/x7abcd</code></td><td>A playlist</td><td>No; open one video from it</td></tr>
<tr><td><code>dailymotion.com/channelname</code></td><td>A channel</td><td>No</td></tr>
<tr><td><code>geo.dailymotion.com/player.html?video=x8k2p1q</code></td><td>Newer embed used by news sites</td><td>Copy the ID after <code>video=</code> and build the <code>/video/</code> link</td></tr>
</tbody></table>
<p>The video ID always starts with <code>x</code> and is six to eight characters. If you have only the ID, type <code>https://www.dailymotion.com/video/</code> followed by it and the page opens.</p>
<h2>Dailymotion on news and sports sites</h2>
<p>Many publishers host their clips on Dailymotion and embed the player on their own pages, so the address bar shows the newspaper's URL, not Dailymotion's. Right-click the player and choose "Copy video URL", or click the Dailymotion logo in the player's corner, which opens the clip on dailymotion.com. Some publisher embeds are set to play only on the publisher's domain; those return nothing from the Dailymotion link, and the publisher's page is the only place to watch them.</p>
<h2>What the quality rows mean on Dailymotion</h2>
<p>Dailymotion stores renditions at 240p, 380p, 480p, 720p and 1080p, and serves whichever ones the upload supports. A broadcaster's clip usually comes back with the full ladder; a phone upload from 2015 may offer 380p only. The rows are MP4 and play on every device. A one-minute 720p clip is roughly 8 to 12 MB; 1080p about 15 to 25 MB. Unlike YouTube, Dailymotion does not use video-only streams, so the 1080p row is ready to play with sound.</p>
<h2>Age-gated, geo-limited and partner videos</h2>
<p>Dailymotion lets uploaders restrict a video to signed-in adults or to certain countries, and some media partners restrict playback to their own apps. Those videos play for you only inside a session or region that qualifies, so a public-link check returns nothing. The page itself tells you: a sign-in wall, a "not available in your country" notice or an app-store prompt all mean the video is not public in the sense a link tool needs.</p>
<h2>Step by step from a dai.ly link</h2>
<ol>
<li>Open the <code>dai.ly</code> link in a browser and wait for the full dailymotion.com page.</li>
<li>Check it plays without signing in.</li>
<li>Copy the address bar, which now shows the <code>/video/x…</code> form.</li>
<li>Paste it into the <a href="/dailymotion-downloader">Dailymotion downloader</a> and compare the rows.</li>
<li>Download the one that fits your screen; keep the link with the file if you need the source later.</li>
</ol>
HTML,
            ],
            'download-vimeo-videos-public-links-passwords-privacy-guide' => [
                'marker' => 'Vimeo URL anatomy',
                'before' => '<h2>Frequently asked questions</h2>',
                'meta_description' => 'How to download Vimeo videos from public links: the six privacy levels, unlisted and player URLs, the creator download button, MP4 renditions and file sizes.',
                'section' => <<<'HTML'
<h2>Vimeo URL anatomy</h2>
<table><thead><tr><th>Address</th><th>Meaning</th><th>Result</th></tr></thead><tbody>
<tr><td><code>vimeo.com/123456789</code></td><td>A public or password video</td><td>Works when public</td></tr>
<tr><td><code>vimeo.com/123456789/a1b2c3d4e5</code></td><td>An unlisted video; the second part is its private hash</td><td>Works, because the link itself is the key; keep the hash</td></tr>
<tr><td><code>player.vimeo.com/video/123456789</code></td><td>The embedded player</td><td>Usually works; if not, use the <code>vimeo.com/ID</code> form</td></tr>
<tr><td><code>vimeo.com/channels/staffpicks/123456789</code></td><td>A video inside a channel</td><td>Works; the ID is what matters</td></tr>
<tr><td><code>vimeo.com/showcase/…</code> or <code>vimeo.com/user/…</code></td><td>Collections and profiles</td><td>No; open one video</td></tr>
<tr><td><code>vimeo.com/review/…</code></td><td>A client review page</td><td>No; review links are private by design</td></tr>
</tbody></table>
<h2>The six Vimeo privacy levels</h2>
<table><thead><tr><th>Setting</th><th>Who can watch</th><th>Public-link check</th></tr></thead><tbody>
<tr><td>Public</td><td>Anyone</td><td>Returns the MP4 renditions</td></tr>
<tr><td>Unlisted</td><td>Anyone with the hashed link</td><td>Returns renditions when the full hashed link is pasted</td></tr>
<tr><td>Hide from Vimeo</td><td>Only where embedded</td><td>Returns nothing from vimeo.com; the embedding site's player is the only way to watch</td></tr>
<tr><td>Password</td><td>People with the password</td><td>Returns nothing; no tool should ask you for the password</td></tr>
<tr><td>Private</td><td>The owner and chosen members</td><td>Returns nothing</td></tr>
<tr><td>Domain-restricted embed</td><td>Visitors of approved websites</td><td>Returns nothing from the direct link</td></tr>
</tbody></table>
<h2>The creator's own download button</h2>
<p>Vimeo lets creators switch on a Download button under their video, often with the original file at full quality. If you see it, use it: it is the version the creator intends to share, it is often larger and sharper than any streamed rendition, and it needs no tool. The public-link check is for public videos whose creators left the button off but allow saving, and for comparing which renditions exist.</p>
<h2>Renditions and sizes</h2>
<p>Vimeo encodes public videos at 240p, 360p, 540p, 720p, 1080p and, for Plus and Pro uploads, 2K and 4K. Older free accounts stop at 720p or 1080p. A five-minute 1080p rendition is typically 60 to 120 MB because Vimeo uses higher bitrates than most platforms; 4K can reach 500 MB for the same length. Vimeo serves full MP4 files with sound at every level, so there is no video-only step.</p>
<h2>Downloading a Vimeo video on a phone</h2>
<p>In the Vimeo app, tap the share arrow on the video and choose Copy link; the app copies the <code>vimeo.com/ID</code> form, with the hash included for unlisted videos. Switch to Chrome or Safari, paste it above and pick a rendition. Vimeo files are larger than social-media clips, so prefer Wi-Fi for 1080p and above: a ten-minute 1080p rendition can be 200 MB. On Android the file lands in Downloads; on iPhone in Files → Downloads, from where Share → Save Video places it in Photos when you have permission to keep it.</p>
<h2>When the video plays but the result is empty</h2>
<p>Two settings cause this. "Hide from Vimeo" videos play on the website that embeds them but their vimeo.com page shows nothing, so the direct link returns no rows; the embedding site is the only place to watch. Domain-restricted embeds behave the same way. In both cases the creator chose to keep the video inside one website, and the honest answer is to ask them for a download or a public link.</p>
<h2>Courses, films and client work</h2>
<p>Vimeo hosts a lot of paid material: Vimeo On Demand films, course videos behind a membership site, and client review cuts. These are private, password or domain-restricted on purpose, and the right route is the one the seller or creator provides. For your own client deliverables, the creator can share the original file through Vimeo's download setting or a review link with downloads enabled, which keeps everyone inside the permission the project agreed.</p>
HTML,
            ],
            'tiktok-mp4-vs-mp3-download-format-guide' => [
                'marker' => 'Short share links and the app-install page',
                'before' => '<h2>Why some formats are missing</h2>',
                'section' => <<<'HTML'
<h2>Short share links and the app-install page</h2>
<p>The link the TikTok app copies is usually a short one that redirects when opened. On a phone without the app, or in a private window, the redirect sometimes lands on a page that pushes the app download instead of the clip. That page still has the clip's address: tap the "Watch in browser" or "Continue" option, wait for the clip to load, then copy the address bar, which now reads <code>tiktok.com/@name/video/…</code>. That full form is the one to paste. If a short link returns no result, this is almost always why, and the full link fixes it.</p>
<h2>Which format for a photo-mode post?</h2>
<p>TikTok's photo mode posts a set of images with a sound. There is no MP4 to return, so the MP3 row is the only download and the images are listed separately when the provider exposes them. If you need the slideshow as a video, the creator's own export is the only accurate source; recreating it from the images and the MP3 in an editor is possible but the timing will not match the original.</p>
<h2>Size and quality in numbers</h2>
<table><thead><tr><th>Clip</th><th>MP4 720p</th><th>MP4 1080p</th><th>MP3 128 kbps</th></tr></thead><tbody>
<tr><td>15 seconds</td><td>3 to 5 MB</td><td>5 to 9 MB</td><td>0.25 MB</td></tr>
<tr><td>60 seconds</td><td>11 to 19 MB</td><td>19 to 38 MB</td><td>1 MB</td></tr>
<tr><td>3 minutes</td><td>35 to 55 MB</td><td>55 to 110 MB</td><td>3 MB</td></tr>
</tbody></table>
<p>The audio row is about one-twentieth of the video row, which is why MP3 is the practical choice for a long spoken clip you only want to hear. For anything with on-screen text, captions or a visual joke, the saving is not worth losing the picture.</p>
HTML,
            ],
            'instagram-reels-download-quality-links-mobile-tips' => [
                'marker' => 'Copying a Reel link on a computer',
                'before' => '<h2>Why an Instagram Reel link may fail</h2>',
                'section' => <<<'HTML'
<h2>Copying a Reel link on a computer</h2>
<p>On instagram.com, a Reel opens in its own page and the address bar shows <code>/reel/</code> plus the shortcode, which you can copy directly. Reels that open in an overlay from the profile grid also update the address bar, so the same copy works. The three-dot menu on the desktop post offers "Copy link" as well, which adds a tracking parameter that the downloader ignores. Desktop is the quickest way to check several Reels from one account: open each, copy, paste, and leave the result tabs open while the downloads finish.</p>
<h2>Carousel posts with several videos</h2>
<p>A feed post with the small stacked-squares icon is a carousel of up to twenty items, and each item can be a photo or a video. A carousel link returns the videos in it as separate rows, labelled in order, so you can save the one you need. Reels are never carousels; if a link shows multiple rows, it is a post, not a Reel, even when Instagram played it in the Reels tab.</p>
<h2>Reels shared to a Story or in a message</h2>
<p>A Reel shared into someone's Story or sent in a DM is still the same Reel. The Story shows a card that opens the original, and the DM contains the original link; tap through to the Reel itself and copy its link from there. A screenshot of a Reel card or a link to the Story carries no video and returns nothing.</p>
HTML,
            ],
            'twitch-clips-vods-live-streams-public-link-guide' => [
                'marker' => 'Twitch URL anatomy',
                'before' => '<h2>Troubleshooting a Twitch link</h2>',
                'excerpt' => 'Twitch clip, VOD and live-channel links explained: which addresses return a file, how long VODs survive, which qualities come back, and what subscriber-only means for a public link.',
                'meta_description' => 'Twitch clip, VOD and live links explained: URL formats, VOD retention by account type, Clips versus Highlights, available qualities and why subscriber-only VODs return nothing.',
                'section' => <<<'HTML'
<h2>Twitch URL anatomy</h2>
<table><thead><tr><th>Address</th><th>What it is</th><th>Returns a file?</th></tr></thead><tbody>
<tr><td><code>clips.twitch.tv/AmusingNameHere</code></td><td>A Clip, 5 to 60 seconds</td><td>Yes</td></tr>
<tr><td><code>twitch.tv/streamer/clip/AmusingNameHere</code></td><td>The same Clip under the channel</td><td>Yes</td></tr>
<tr><td><code>twitch.tv/videos/1234567890</code></td><td>A VOD or Highlight</td><td>Yes while it exists and is public</td></tr>
<tr><td><code>twitch.tv/videos/1234567890?t=1h20m05s</code></td><td>A VOD with a timestamp</td><td>Yes; the whole VOD is returned, note the time to trim</td></tr>
<tr><td><code>twitch.tv/streamer</code></td><td>The channel, live or offline</td><td>No</td></tr>
<tr><td><code>twitch.tv/streamer/videos</code> or <code>/clips</code></td><td>Lists of VODs or Clips</td><td>No; open one item</td></tr>
<tr><td><code>m.twitch.tv/…</code></td><td>Mobile web</td><td>Yes, same IDs</td></tr>
</tbody></table>
<h2>How long a VOD survives</h2>
<table><thead><tr><th>Account type</th><th>Past broadcasts kept for</th><th>Highlights</th></tr></thead><tbody>
<tr><td>Standard account</td><td>7 days</td><td>Kept until deleted</td></tr>
<tr><td>Affiliate</td><td>14 days</td><td>Kept until deleted</td></tr>
<tr><td>Partner, Turbo, Prime</td><td>60 days</td><td>Kept until deleted</td></tr>
</tbody></table>
<p>Past broadcasts also exist only if the streamer switched on "Store past broadcasts". A streamer who did not will have Clips but no VODs at all. Highlights are segments the streamer cut from a VOD and saved permanently, so a Highlight link is the one to keep when a stream matters to you. Clips survive the deletion of the VOD they came from.</p>
<h2>Qualities that come back</h2>
<p>A VOD is stored at the quality the stream was sent in, typically 1080p60 or 936p60 for partners and 720p or 720p60 for most others, plus lower transcodes (480p, 360p, 160p) when Twitch generated them. Clips are stored at the quality of the source at the moment they were made, usually 1080p60 or 720p60, and sometimes only 480p for an older Clip. A one-hour 1080p60 VOD is roughly 2.5 to 4 GB; a 30-second Clip is 5 to 15 MB. Twitch delivers both as MP4 with sound, so no joining step is needed.</p>
<h2>Subscriber-only VODs, muted audio and chat</h2>
<p>Partners and affiliates can make past broadcasts subscriber-only. Those VODs play only for subscribers inside their own session, so a public-link check returns nothing; Clips from the same stream stay public. Twitch also mutes sections of VODs where it detects copyrighted music, and the muted stretches are silent in the file too. Chat is a separate stream of text and is never part of the video.</p>
<h2>Clips, Highlights and VOD segments: which to use when</h2>
<table><thead><tr><th>You want</th><th>Best source</th><th>Why</th></tr></thead><tbody>
<tr><td>A single play, reaction or joke</td><td>Clip</td><td>Small, permanent, already trimmed</td></tr>
<tr><td>A full match, boss fight or interview</td><td>VOD with a timestamp, then trim</td><td>Clips cannot exceed 60 seconds</td></tr>
<tr><td>A whole stream to keep</td><td>Highlight if the streamer made one, otherwise the VOD before it expires</td><td>Highlights never expire</td></tr>
<tr><td>Something from months ago</td><td>Clips or Highlights only</td><td>The VOD is already gone</td></tr>
</tbody></table>
<h2>Downloading on a phone</h2>
<p>In the Twitch app, tap Share on a Clip or VOD and choose Copy link. Paste it into Chrome or Safari rather than the app's own browser. A Clip downloads in seconds; a multi-hour VOD can be several gigabytes, so check free space and stay on Wi-Fi. On iPhone, Safari saves to Files → Downloads; on Android, to the Downloads folder.</p>
<h2>Making your own Clip first</h2>
<p>If the moment you want is inside a long VOD and under a minute, make a Clip of it on Twitch before downloading: open the VOD, move to the moment, press the Clip button (or Alt+X), set the range and title, and publish. The Clip gets its own permanent link, downloads in seconds as a small MP4, and survives after the VOD expires, which saves trimming a multi-gigabyte file on your device.</p>
<h2>Reruns and premieres</h2>
<p>A channel showing a rerun or a premiere is replaying a VOD, but the channel page is still a live page and returns nothing. Open the channel's Videos tab, find the broadcast being replayed, and use that <code>/videos/</code> link instead.</p>
<h2>Clip length and the 60-second limit</h2>
<p>A Clip is at most 60 seconds, chosen by the person who made it. For a longer moment, use the VOD with a timestamp and trim the file afterwards; the <a href="/blog/download-youtube-clips-clip-link-vs-watch-link-guide">YouTube clips guide</a> shows the same trimming method with FFmpeg and LosslessCut, which works for Twitch MP4s unchanged.</p>
HTML,
            ],
            'save-video-public-x-post-correct-url' => [
                'marker' => 'X URL anatomy',
                'before' => '<h2>Protected and unavailable posts</h2>',
                'excerpt' => 'How x.com and twitter.com status links work, which link shapes return a video, the bitrates X serves, GIFs that arrive as MP4, and why protected or deleted posts cannot be processed.',
                'meta_description' => 'Save a video from a public X post: the correct status URL, t.co and /i/status/ links, the three MP4 bitrates X serves, GIFs as MP4, multi-video posts, and protected accounts.',
                'section' => <<<'HTML'
<h2>X URL anatomy</h2>
<table><thead><tr><th>Address</th><th>Meaning</th><th>Use it?</th></tr></thead><tbody>
<tr><td><code>x.com/username/status/1712345678901234567</code></td><td>One post; the long number is the post ID</td><td>Yes</td></tr>
<tr><td><code>twitter.com/username/status/…</code></td><td>The same post on the old domain</td><td>Yes; it redirects</td></tr>
<tr><td><code>x.com/i/status/1712345678901234567</code></td><td>The post without the username</td><td>Yes</td></tr>
<tr><td><code>x.com/username/status/…/video/1</code></td><td>The post's first video opened full-screen</td><td>Yes; the <code>/video/1</code> part can stay</td></tr>
<tr><td><code>t.co/AbCdEf123</code></td><td>X's short link, used in emails and other apps</td><td>Open it once and copy the final address</td></tr>
<tr><td><code>x.com/username</code>, <code>/home</code>, <code>/search?q=…</code></td><td>Profile, timeline, search</td><td>No</td></tr>
<tr><td><code>x.com/i/spaces/…</code> or <code>/i/broadcasts/…</code></td><td>Spaces audio and live broadcasts</td><td>No; not standard video posts</td></tr>
</tbody></table>
<h2>The three bitrates X serves</h2>
<p>X re-encodes every uploaded video into up to three MP4 renditions at different sizes: around 320 × 568, 640 × 1136 and 1280 × 720 or 720 × 1280 for vertical clips, at roughly 0.4, 0.8 and 2 Mbps. The result lists them by bitrate rather than by a tidy "720p" label, so pick the largest row for the sharpest copy. A two-minute clip is about 6 MB, 12 MB and 30 MB across the three. X never serves 1080p for ordinary accounts; a sharper source exists only on the platform the creator uploaded from.</p>
<h2>GIFs, multi-video posts and quote posts</h2>
<p>A "GIF" on X is stored as a short, silent MP4, so a GIF post returns one small MP4 row and that file plays everywhere. A post can hold up to four videos; each appears as its own row, in the order shown on the post. A quote post embeds another post: if the video belongs to the quoted post, open that post and copy its link, because the quoting post's link returns only media the quoting user attached.</p>
<h2>Sensitive-media and age-restricted posts</h2>
<p>Posts marked as sensitive play only after you confirm a warning, and some play only for signed-in adults. The first kind is still public and returns rows; the second kind is hidden from signed-out viewers and returns nothing. Opening the post in a private window shows which case you have.</p>
<h2>Videos inside replies and threads</h2>
<p>A thread is a chain of separate posts, each with its own ID. The video you saw while scrolling belongs to one specific post in the chain, so tap that post to open it on its own page and copy its link; the first post's link returns only the first post's media. The same applies to replies under a post, and to posts inside a Community, which use ordinary status links and work when the Community is public.</p>
<h2>Premium uploads and long videos</h2>
<p>Standard accounts can post videos up to 2 minutes 20 seconds. Premium subscribers can upload much longer videos, up to several hours, and X serves those at 1080p when the upload supports it. The result for a long video lists more and larger rows, and the top row can reach 1 to 2 GB for a two-hour upload. If a long video shows only low rows, X is still processing the higher renditions; try again after a while.</p>
<h2>Posts that embed a YouTube or TikTok link</h2>
<p>Many posts share a link to a video hosted elsewhere, shown as a preview card rather than an uploaded clip. A card has no X video to return; tap it, open the video on YouTube, TikTok or wherever it lives, and use that platform's own page on this site instead.</p>
<h2>Keeping the post with the video</h2>
<p>A saved MP4 carries no text, username or date. If you may need the context later, copy the post's text and link into a note next to the file, or take a screenshot of the post; X deletes posts and suspends accounts often enough that the link alone may not survive.</p>
<h2>Step by step on a phone</h2>
<ol>
<li>Tap the share icon under the post and choose Copy link.</li>
<li>Switch to Chrome or Safari; the browser inside the X app blocks file downloads.</li>
<li>Paste the link into the <a href="/twitter-downloader">X / Twitter downloader</a>.</li>
<li>Choose the largest MP4 row unless storage is tight.</li>
<li>Find the file in Downloads (Android) or Files → Downloads (iPhone).</li>
</ol>
HTML,
            ],
        ];
    }

    public function down()
    {
        DB::table('blog_posts')->whereIn('slug', array_keys(self::MERGES))->update(['status' => 'published', 'updated_at' => now()]);
        // Merged and expanded content is intentionally retained.
    }
}

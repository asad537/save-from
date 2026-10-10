<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddPlatformSpecificSearchSections extends Migration
{
    public function up()
    {
        $now = now();
        foreach ($this->sections() as $slug => $section) {
            $site = DB::table('supported_sites')->where('slug', $slug)->first();
            if (!$site || strpos($site->description, $section['key']) !== false) {
                continue;
            }

            DB::table('supported_sites')->where('id', $site->id)->update([
                'description' => rtrim($site->description)."\n".$section['html'],
                'updated_at' => $now,
            ]);
        }
    }

    public function down()
    {
        // Content additions are intentionally retained after publication.
    }

    private function sections()
    {
        return [
            'youtube-downloader' => ['key' => 'YouTube link anatomy: the video ID', 'html' => <<<'HTML'
<h2>YouTube link anatomy: the video ID</h2>
<p>A YouTube downloader does not need a particular-looking share URL; it needs the eleven-character video ID. In a watch link it appears after <code>v=</code>, in a short share link it follows <code>youtu.be/</code>, and in a Short it follows <code>/shorts/</code>. Those addresses can all identify the same public upload. A playlist URL is different: it identifies a collection, even when it happens to open a video first. Copy the individual video's Share link when you want one result.</p>
<p>This also explains why timestamps and share parameters are harmless. Values such as <code>t=</code> and <code>si=</code> change where playback begins or how the link was shared, not the underlying video. The returned quality still depends on the upload, its age and the streams YouTube has made available.</p>
HTML],
            'instagram-downloader' => ['key' => 'Instagram URLs: posts, Reels and Stories', 'html' => <<<'HTML'
<h2>Instagram URLs: posts, Reels and Stories</h2>
<p>Instagram uses different address paths for different media. A Reel normally contains <code>/reel/</code>, an ordinary video post uses <code>/p/</code>, and a Story is tied to an account and disappears after its viewing window. Copy the address of the actual post—not a profile, hashtag, Explore result or saved collection. A public Reel can still fail if its creator changes the account to private after you copied the link.</p>
<p>On mobile, copy from the three-dot menu inside the post rather than sharing the account page. The returned format is normally MP4, but Instagram's original upload settings and recompression determine the resolution. A vertical Reel and a landscape feed video should be treated as different source files, not interchangeable quality options.</p>
HTML],
            'tiktok-downloader' => ['key' => 'TikTok share links and redirect checks', 'html' => <<<'HTML'
<h2>TikTok share links and redirect checks</h2>
<p>TikTok links often begin with a short redirect domain before opening a full video address. That is normal: open the share link once in a browser if the provider cannot identify it, then copy the final URL for the individual video. A For You feed, sound page, creator profile or search result is not a video address and cannot return one file.</p>
<p>Creators can change availability quickly by deleting a post, setting it private or restricting it by region. When that happens, a link that worked yesterday may not return formats today. Save-Froms does not remove watermarks, bypass privacy controls or recreate deleted videos; it only lists streams that a public source currently provides.</p>
HTML],
            'facebook-downloader' => ['key' => 'Facebook audience setting is the deciding factor', 'html' => <<<'HTML'
<h2>Facebook audience setting is the deciding factor</h2>
<p>For Facebook, the audience setting matters more than the URL shape. A Watch link, Reel or Page video can be processed only when it opens for a signed-out visitor. “Friends”, group-only and private Page content may look public while you are logged in, but a downloader cannot use your Facebook session. Test the link in a private browser window before trying it here.</p>
<p>Facebook also creates several ways to reach the same post: <code>watch/?v=</code>, a Reel path, a Page permalink or a shared short link. Use the Share → Copy link option from the video itself. If the copied address opens a feed rather than the post, open the video in full-screen first and copy again.</p>
HTML],
            'twitter-downloader' => ['key' => 'X post URLs must include a status ID', 'html' => <<<'HTML'
<h2>X post URLs must include a status ID</h2>
<p>An X/Twitter media link needs a post address with <code>/status/</code> followed by its numeric ID. The same post can use either <code>x.com</code> or <code>twitter.com</code>; both names point to the same service. Profile pages, search URLs, lists and Spaces do not identify one media item, so they cannot return a video row.</p>
<p>Posts protected by the author's account setting remain unavailable even if you can view them while signed in. Quote posts are another common source of confusion: copy the post containing the actual video, not the quote that merely embeds it. Resolution options reflect the version uploaded to that post and may differ from a re-upload elsewhere.</p>
HTML],
            'vimeo-downloader' => ['key' => 'Vimeo privacy settings are stricter than a public player', 'html' => <<<'HTML'
<h2>Vimeo privacy settings are stricter than a public player</h2>
<p>Vimeo lets a creator embed a video on another website while restricting the original Vimeo page, which is why an embedded player is not always a usable public link. Password-protected videos, unlisted videos without the required token and “hide from Vimeo” embeds may play in one location yet not expose a public file. Open the title in the player and copy the individual Vimeo URL when it is available.</p>
<p>Vimeo creators also choose whether viewers may download an original file. Save-Froms does not override that choice. When a public rendition is returned, its quality is based on Vimeo's transcode rather than the camera-original upload.</p>
HTML],
            'dailymotion-downloader' => ['key' => 'Dailymotion dai.ly links point to one video', 'html' => <<<'HTML'
<h2>Dailymotion dai.ly links point to one video</h2>
<p>Dailymotion's <code>dai.ly</code> short links are useful because they normally redirect to a single video ID. They can be pasted directly, or opened once so the browser reveals the full <code>dailymotion.com/video/</code> address. A channel page and a playlist are collections, not files; choose the individual clip within them.</p>
<p>Broadcast and news videos on Dailymotion are frequently replaced, geo-limited or removed when rights expire. In those cases no browser downloader can keep serving the previous rendition. If the link plays for everyone and still returns no formats, request a fresh analysis later because the provider's temporary media manifest may have changed.</p>
HTML],
            'twitch-downloader' => ['key' => 'Twitch Clips, VODs and live streams are different', 'html' => <<<'HTML'
<h2>Twitch Clips, VODs and live streams are different</h2>
<p>A Twitch Clip is a short, finished highlight with its own share link, while a VOD is a past broadcast and a live channel is still being created in real time. Clips are the most reliable link type because the recording has already ended. A live stream cannot become a downloadable file until the streamer finishes and Twitch publishes a replay, if the creator has enabled VOD storage.</p>
<p>VODs can also disappear when the creator deletes them or Twitch's retention period ends. Copy the specific Clip or video URL, not <code>twitch.tv/channelname</code>. Subscriber-only streams and clips restricted by the creator are not public media even when a preview appears in a browser.</p>
HTML],
        ];
    }
}

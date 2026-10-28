<?php

namespace App\Support;

use App\Models\BlogPost;

/**
 * Topic hubs for the blog (/guides/{topic}). Each hub lists the English
 * guides whose slug carries one of its tokens, so posts on page 2+ of the
 * main listing get a strong, crawlable parent page.
 */
class GuideTopics
{
    const TOPICS = [
        'youtube' => [
            'name' => 'YouTube',
            'h1' => 'YouTube Download Guides',
            'title' => 'YouTube Download Guides – Links, Formats & 4K | Save-Froms',
            'description' => 'All YouTube download guides: watch and Shorts links, MP4 vs WEBM, 1080p and 4K, MP3 audio, clips, and fixes when a download fails.',
            'tokens' => ['youtube', 'yt', 'shorts', 'ssyoutube'],
            'tool' => 'youtube-downloader',
            'intro' => '<p>YouTube is the platform people download from most, and also the one with the most moving parts: three link shapes, two container formats, resolutions that arrive as video-only streams, and separate audio rows. These guides cover each part on its own. Start with the link guides if a video returns nothing, the format guides if you are choosing between MP4, WEBM and MP3, and the quality guides before downloading anything above 1080p.</p><p>Every guide was written against the live <a href="/youtube-downloader">YouTube video downloader</a>, so the row names and sizes it mentions are the ones you will see on the result page.</p>',
        ],
        'instagram' => [
            'name' => 'Instagram',
            'h1' => 'Instagram Download Guides',
            'title' => 'Instagram Download Guides – Reels & Stories | Save-Froms',
            'description' => 'Guides for saving public Instagram Reels, video posts and Stories: the right link, the quality Instagram serves, private accounts and phone steps.',
            'tokens' => ['instagram', 'ig', 'baixar'],
            'tool' => 'instagram-downloader',
            'intro' => '<p>Instagram serves one public rendition per Reel or video, keeps Stories for only 24 hours, and hides everything on a private account. Most failed downloads come from one of those three facts rather than from the tool. The guides below explain the link types (/reel/, /p/, /stories/), what quality to expect, and how to find the saved file on Android and iPhone.</p><p>For a quick result, paste a public Reel link into the <a href="/instagram-reels-downloader">Instagram Reels downloader</a> or a live Story link into the <a href="/instagram-stories-downloader">Instagram Stories downloader</a>.</p>',
        ],
        'tiktok' => [
            'name' => 'TikTok',
            'h1' => 'TikTok Download Guides',
            'title' => 'TikTok Download Guides – Watermark, MP4 & MP3 | Save-Froms',
            'description' => 'Guides for downloading public TikTok videos: vm/vt links, MP4 without the watermark, MP3 sound, photo-mode posts, quality, and what fails and why.',
            'tokens' => ['tiktok'],
            'tool' => 'tiktok-downloader',
            'intro' => '<p>TikTok links come in short and long forms, clips have a watermarked and a clean rendition, and every post carries a sound that can be saved on its own. These guides sort out which link to paste, when a watermark-free MP4 is returned, what the MP3 row really contains, and how photo-mode posts behave.</p><p>The <a href="/tiktok-downloader">TikTok video downloader</a> accepts full and short links; the <a href="/tiktok-mp4-downloader">MP4</a> and <a href="/tiktok-mp3-downloader">MP3</a> pages go deeper on each format.</p>',
        ],
        'facebook' => [
            'name' => 'Facebook',
            'h1' => 'Facebook Download Guides',
            'title' => 'Facebook Download Guides – Videos, Reels & HD | Save-Froms',
            'description' => 'Guides for saving public Facebook videos, Reels and Stories: fb.watch links, SD vs HD, audience settings, muted music, phone and desktop steps.',
            'tokens' => ['facebook', 'fb'],
            'tool' => 'facebook-downloader',
            'intro' => '<p>Facebook video lives in more places than any other platform: Watch, Reels, Page posts, groups, Stories and fb.watch share links. Whether a link returns a file depends on the post\'s audience setting, and whether you get HD depends on how the creator uploaded. The guides below cover each link shape and each surprise, from silent downloads to Reels that exist only in SD.</p><p>Paste any public video or Reel link into the <a href="/facebook-downloader">Facebook video downloader</a>; the <a href="/facebook-reels-downloader">Facebook Reels downloader</a> covers the vertical format specifically.</p>',
        ],
        'other-platforms' => [
            'name' => 'Vimeo, Dailymotion, Twitch, X & Threads',
            'h1' => 'Vimeo, Dailymotion, Twitch, X and Threads Guides',
            'title' => 'Vimeo, Dailymotion, Twitch, X & Threads Guides | Save-Froms',
            'description' => 'Guides for Vimeo privacy levels, Dailymotion and dai.ly links, Twitch clips and VOD retention, X status links and bitrates, and Threads posts.',
            'tokens' => ['vimeo', 'dailymotion', 'twitch', 'x', 'twitter', 'threads'],
            'tool' => 'supported-sites',
            'intro' => '<p>Each of these platforms has one quirk that decides whether a download works: Vimeo\'s six privacy levels, Dailymotion\'s dai.ly redirects and embedded players, Twitch VODs that expire after a week or two, X posts that serve three bitrates, and Threads posts that may be a reply or a quote of another post. The guides here are short on theory and long on the exact link to copy.</p><p>The <a href="/supported-sites">supported sites</a> page lists what each platform can return and links to its downloader.</p>',
        ],
        'formats-and-quality' => [
            'name' => 'Formats & quality',
            'h1' => 'Video Format and Quality Guides',
            'title' => 'Video Format & Quality Guides – MP4, MP3, 4K | Save-Froms',
            'description' => 'Guides on choosing a download format and quality: MP4 vs WEBM vs MP3, 360p to 4K file sizes, converters versus audio rows, and real resolution limits.',
            'tokens' => ['mp3', 'mp4', 'resolution', 'audio', 'converter', '4k', 'format', 'formats'],
            'tool' => 'how-to-download-videos',
            'intro' => '<p>The result page lists every rendition a platform returns, and the right choice depends on where the file will be watched. These guides explain what the container badges mean, how resolution and bitrate set file size, why 1080p and 4K rows take longer, when an audio-only row beats converting a video, and why no tool can add quality the upload never had.</p><p>If you are new to this, read <a href="/how-to-download-videos">how to download videos online</a> first; it walks through the result page row by row.</p>',
        ],
        'fixes-and-safety' => [
            'name' => 'Fixes & safety',
            'h1' => 'Download Fixes and Safety Guides',
            'title' => 'Video Download Fixes & Safety Guides | Save-Froms',
            'description' => 'Guides for when a video download is not working and for staying safe: link and 403 errors, expired results, Preparing that never ends, safe tools.',
            'tokens' => ['working', 'fix', 'fixes', '403', 'safety', 'savefrom', 'videosaver', 'alternative', 'search', 'web'],
            'tool' => 'download-troubleshooting',
            'intro' => '<p>Almost every failed download has one of five causes: the link points to a collection, the post is not public, the result expired, the platform changed something, or the browser blocked the file. These guides show how to tell them apart in under a minute, and what the messages on this site mean. The safety guides cover what people searching for "savefrom" and similar names should avoid: installs, logins and fake download buttons.</p><p>The <a href="/download-troubleshooting">download troubleshooting page</a> has the full error table; the guides below go deeper on each case.</p>',
        ],
        'hindi' => [
            'name' => 'Hindi / Hinglish',
            'h1' => 'Hindi Mein Video Download Guides',
            'title' => 'Hindi Mein Video Download Guides | Save-Froms',
            'description' => 'Hinglish guides: YouTube se video download kaise kare, Instagram se Reels kaise download kare, aur video download karne wala app ki jagah browser ka tareeqa.',
            'tokens' => [],
            'language' => 'hi-Latn',
            'tool' => 'how-to-download-videos',
            'intro' => '<p>Yeh guides un sawalon ke jawab hain jo India mein log Google par roz type karte hain: YouTube se video download kaise kare, Instagram se Reels kaise download kare, aur kaunsa video download karne wala app sahi hai. Jawab har jagah ek hi hai: app ki zaroorat nahi, Chrome ya Safari mein link paste karo aur file save ho jati hai. Har guide Android, iPhone aur computer ke steps alag alag deta hai, aur errors ke hal bhi.</p><p>Angrezi guides <a href="/blog">blog</a> par hain; tool pages, jaise <a href="/youtube-downloader">YouTube downloader</a>, sab ke liye ek jaise kaam karte hain.</p>',
        ],
    ];

    public static function all(): array
    {
        return self::TOPICS;
    }

    public static function find(string $slug): ?array
    {
        return isset(self::TOPICS[$slug]) ? self::TOPICS[$slug] + ['slug' => $slug] : null;
    }

    public static function posts(string $slug)
    {
        $topic = self::find($slug);
        if (!$topic) {
            return collect();
        }

        return BlogPost::published()->where('language', $topic['language'] ?? 'en')->latest('published_at')->latest('id')->get()
            ->filter(function ($post) use ($topic) {
                return !$topic['tokens'] || array_intersect($topic['tokens'], explode('-', $post->slug));
            })->values();
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Second live audit (2026-10-08): expand the thin YouTube Shorts / YouTube to MP4
 * pages with intent-specific sections, give the MP3 page an exact-match H1,
 * add Android/iPhone and quality sections to Facebook and Dailymotion, align
 * titles with the title strategy, and add three high-demand FAQ entries.
 */
class ExpandThinPagesAndTitles extends Migration
{
    public function up()
    {
        $now = now();

        foreach ($this->landingPages() as $slug => $data) {
            if (isset($data['faq_items'])) {
                $data['faq_items'] = json_encode($data['faq_items'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
            DB::table('landing_pages')->where('slug', $slug)->update($data + ['updated_at' => $now]);
        }

        foreach ($this->titles() as $slug => $title) {
            DB::table('supported_sites')->where('slug', $slug)->update(['meta_title' => $title, 'updated_at' => $now]);
        }

        foreach ($this->platformAdditions() as $slug => $addition) {
            $site = DB::table('supported_sites')->where('slug', $slug)->first();
            if (!$site) {
                continue;
            }
            $marker = $addition['before'];
            $html = $site->description;
            if (strpos($html, $marker) !== false && strpos($html, $addition['key']) === false) {
                $html = str_replace($marker, $addition['html'].$marker, $html);
                DB::table('supported_sites')->where('id', $site->id)->update(['description' => $html, 'updated_at' => $now]);
            }
        }

        $order = (int) DB::table('faqs')->max('sort_order');
        foreach ($this->faqs() as $faq) {
            if (DB::table('faqs')->where('question', $faq[1])->exists()) {
                continue;
            }
            DB::table('faqs')->insert([
                'question' => $faq[1], 'answer' => $faq[2], 'category' => $faq[0],
                'sort_order' => ++$order, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }
    }

    public function down()
    {
        // Content-only change.
    }

    private function titles()
    {
        return [
            'instagram-downloader' => 'Instagram Video Downloader – Reels & Videos | Save-Froms',
            'twitter-downloader' => 'Twitter/X Video Downloader – Download Public Videos | Save-Froms',
            'vimeo-downloader' => 'Vimeo Video Downloader – MP4 & HD | Save-Froms',
            'twitch-downloader' => 'Twitch Clip Downloader – Clips & Public VODs | Save-Froms',
        ];
    }

    private function faqs()
    {
        return [
            ['About Save-Froms', 'Is Save-Froms safe to use?', '<p>Save-Froms never asks for a password, installs nothing, and streams files without storing them. The address bar should always show <strong>save-froms.net</strong>; there is one Download button per format and no pop-ups demanding an app. What little data is recorded is listed in the <a href="/privacy-policy">Privacy Policy</a>, and the people behind the site are described on the <a href="/about">About page</a>.</p>'],
            ['Formats & Quality', 'Can I download 1080p or 4K video?', '<p>Yes, when the source offers it. On YouTube, resolutions above 720p are stored as separate video and audio streams, so those options are assembled on demand and often arrive as WEBM; details are on the <a href="/youtube-to-mp4">YouTube to MP4</a> page. Other platforms return whatever renditions the uploader provided, usually up to 1080p. Save-Froms never upscales a lower-quality upload.</p>'],
            ['Downloads', 'Why is my download stuck, or why do I see a 403 error?', '<p>A button stuck on <em>Preparing</em> usually means a long video is still being assembled; wait, or pick the next quality down. A 403 or "forbidden" response means the temporary media address has expired or the source blocked the request: analyse the link again to get a fresh result. The <a href="/download-troubleshooting">troubleshooting guide</a> walks through every message, and the <a href="/blog/savefrom-not-working-link-preparing-403-fixes">403 and Preparing article</a> covers the causes in depth.</p>'],
        ];
    }

    private function platformAdditions()
    {
        return [
            'facebook-downloader' => [
                'key' => 'Download a Facebook video on Android or iPhone',
                'before' => '<h2>Facebook-specific troubleshooting</h2>',
                'html' => <<<'HTML'
<h2>Download a Facebook video on Android or iPhone</h2>
<p>The Facebook app has no save option for other people's videos, so the browser route is the practical one. On <strong>Android</strong>: tap the three dots on the video, choose <strong>Copy link</strong>, open Chrome, paste the link on this page and tap Download; the file lands in Downloads and shows up in Google Photos or Files. On <strong>iPhone</strong>: use the same Copy link menu, open Safari, paste and download; Safari puts the MP4 in Files → Downloads, from where the share sheet can save it to Photos. Both routes work only for videos whose audience is Public.</p>

<h2>Facebook video quality explained</h2>
<p>Facebook re-encodes every upload and keeps two renditions for public playback: an SD version, usually 360p to 480p, and an HD version at 720p or 1080p when the original was large enough. Both are H.264 MP4, so they play everywhere. Reels are vertical 1080×1920 at a modest bitrate and are often 5–20 MB; a ten-minute Page video at HD is typically 80–200 MB. Older uploads, screen recordings and videos first posted at low resolution may exist in SD only, and Save-Froms cannot create an HD version that Facebook never stored.</p>

HTML,
            ],
            'dailymotion-downloader' => [
                'key' => 'Dailymotion HD, 1080p and file sizes',
                'before' => '<h2>Dailymotion-specific troubleshooting</h2>',
                'html' => <<<'HTML'
<h2>Dailymotion HD, 1080p and file sizes</h2>
<p>Dailymotion transcodes uploads into fixed renditions: 144p, 240p, 380p, 480p, 720p and 1080p, each an H.264 MP4. A video shows the 720p and 1080p rows only when the original upload was at least that large; many broadcaster clips stop at 480p. As rough guides for a five-minute video, 380p is about 15–30 MB, 720p about 50–100 MB and 1080p about 100–200 MB. The estimated size next to each row comes from the provider and is the number to trust for your own clip.</p>

<h2>Download on Android or iPhone</h2>
<p>In the Dailymotion app, tap <strong>Share → Copy link</strong>; you will get a dai.ly short link, which works here. On <strong>Android</strong>, open Chrome, paste it on this page and tap Download; the MP4 is saved to Downloads. On <strong>iPhone</strong>, use Safari; the file goes to Files → Downloads and can be moved to Photos with the share sheet. If the short link fails on a phone, open it in the browser first so it redirects to the full dailymotion.com/video address, then copy that.</p>

HTML,
            ],
        ];
    }

    private function landingPages()
    {
        return [
            'youtube-mp3-downloader' => [
                'title' => 'YouTube to MP3 Downloader',
                'eyebrow' => 'YouTube Audio',
                'heading' => 'YouTube to [MP3] Downloader',
                'intro' => 'Paste a public YouTube video link and save the MP3 audio track the provider returns for it, in the bitrates that are actually available for that video.',
                'meta_title' => 'YouTube to MP3 Downloader – Available Audio | Save-Froms',
                'meta_description' => 'Convert a public YouTube link to MP3 when an audio track is returned. Compare bitrate and file size before saving permitted music, podcasts or lectures.',
            ],

            'youtube-shorts-downloader' => [
                'meta_title' => 'YouTube Shorts Downloader – Save Shorts on Mobile | Save-Froms',
                'meta_description' => 'Download public YouTube Shorts as MP4. Learn which Shorts links work, how to save a Short on Android or iPhone, why a Short can fail and what quality to expect.',
                'content' => <<<'HTML'
<h2>What is a YouTube Shorts URL?</h2>
<p>A Short is an ordinary YouTube video, at most three minutes long, shown in a vertical player. Its address uses the <code>/shorts/</code> path followed by the same 11-character video ID every YouTube video has. Because the ID is what matters, the Short can also be opened as <code>youtube.com/watch?v=ID</code> or <code>youtu.be/ID</code>, and all three forms work in this downloader. Copy the individual Short rather than the Shorts feed or a channel's Shorts tab, which have no video ID of their own.</p>
<div class="tool-steps"><div><strong>Open the Short</strong><span>Tap it so it fills the screen.</span></div><div><strong>Share → Copy link</strong><span>Or copy the address bar on the web.</span></div><div><strong>Paste it above</strong><span>Shorts, watch and youtu.be links all work.</span></div><div><strong>Pick a size</strong><span>Compare the vertical MP4 rows and download.</span></div></div>

<h2>YouTube Shorts vs regular YouTube videos</h2>
<table><thead><tr><th></th><th>Short</th><th>Regular video</th></tr></thead><tbody>
<tr><td>Orientation</td><td>Vertical 9:16 (1080×1920 at best)</td><td>Usually landscape 16:9</td></tr>
<tr><td>Length</td><td>Up to 3 minutes</td><td>Any length</td></tr>
<tr><td>Typical qualities returned</td><td>360p, 480p, 720p, sometimes 1080p</td><td>Up to 4K when uploaded</td></tr>
<tr><td>Typical file size</td><td>2–25 MB</td><td>Tens of MB to several GB</td></tr>
<tr><td>Audio</td><td>Inside the MP4; a separate MP3 row when returned</td><td>Same</td></tr>
<tr><td>Link forms</td><td>/shorts/ID, watch?v=ID, youtu.be/ID</td><td>watch?v=ID, youtu.be/ID</td></tr>
</tbody></table>
<p>Everything else, including privacy rules and the 20-minute result window, is the same as on the main <a href="/youtube-downloader">YouTube video downloader</a>.</p>

<h2>Which Shorts links are supported?</h2>
<ul>
<li><code>youtube.com/shorts/VIDEO_ID</code> copied from the Share menu, with or without the <code>?feature=share</code> suffix.</li>
<li><code>youtu.be/VIDEO_ID</code> short links generated by the app.</li>
<li><code>m.youtube.com/shorts/VIDEO_ID</code> from the mobile site.</li>
<li>A Short opened as a normal watch page.</li>
</ul>
<p>Not supported: the Shorts feed (<code>youtube.com/shorts</code> with no ID), a channel's Shorts tab, a Shorts playlist, or a Short that is private, age-restricted or set to members only.</p>

<h2>How to download a YouTube Short on Android</h2>
<ol>
<li>Open the Short in the YouTube app and tap <strong>Share</strong>, then <strong>Copy link</strong>.</li>
<li>Open Chrome and go to this page; paste the link into the box and tap <strong>Get Download Options</strong>.</li>
<li>Choose a resolution. 720p is a good match for a phone screen; 1080p is worth it only if the Short was uploaded at that size.</li>
<li>Tap <strong>Download</strong>. Chrome saves the MP4 to the Downloads folder; it appears in Google Photos or Gallery within a few seconds.</li>
</ol>

<h2>How to download a YouTube Short on iPhone</h2>
<ol>
<li>Tap <strong>Share → Copy link</strong> on the Short in the YouTube app.</li>
<li>Open Safari, visit this page, paste the link and tap <strong>Get Download Options</strong>.</li>
<li>Tap <strong>Download</strong> next to a resolution. Safari shows a download arrow at the top of the screen and stores the file in <strong>Files → Downloads</strong>.</li>
<li>To put the Short in your camera roll, open it from Files, tap the share icon and choose <strong>Save Video</strong>.</li>
</ol>
<p>If Safari opens the video instead of downloading it, long-press the Download button and choose <strong>Download Linked File</strong>.</p>

<h2>Why a YouTube Shorts download may fail</h2>
<ul>
<li><strong>Feed link instead of a Short:</strong> the URL has no video ID. Open the individual Short and copy again.</li>
<li><strong>Private, members-only or age-restricted Short:</strong> it requires a signed-in viewer, so no public-link tool can fetch it.</li>
<li><strong>Removed or region-blocked:</strong> if the Short does not play in a private browser window, it cannot be processed.</li>
<li><strong>Result expired:</strong> download buttons stay valid for about 20 minutes; paste the link again for a fresh set.</li>
<li><strong>Only one resolution listed:</strong> that is all YouTube stores for that Short, often the case for very recent or very old uploads.</li>
</ul>
<div class="note"><strong>Permission matters:</strong> download only Shorts you created or have the creator's permission to save. A public Short is not a free-to-reuse Short.</div>

<h2>YouTube Shorts quality guide</h2>
<p>Shorts are uploaded from phones, so the top quality is usually 1080×1920 and many are 720×1280. Downloading cannot add detail, change a portrait video to landscape, or remove compression artefacts in the original. For messaging apps, 480p keeps the file under 10 MB; for keeping your own Shorts as a backup, take the highest row offered. The <a href="/blog/download-youtube-shorts-on-mobile">Shorts on mobile guide</a> shows the full workflow with screenshots, and the <a href="/blog/video-resolution-vs-file-size-360p-720p-1080p">resolution and file-size guide</a> explains why two 720p files can differ in size.</p>
HTML,
                'faq_items' => [
                    ['question' => 'Can I use a regular YouTube link instead of a Shorts link?', 'answer' => 'Yes. A watch URL or youtu.be link that points to the same video ID is processed exactly like the /shorts/ link.'],
                    ['question' => 'Does the downloaded Short include the music?', 'answer' => 'The MP4 contains the audio that plays on YouTube. If a separate audio-only row is returned you can save just the sound, but licensed music remains the property of its rights holder.'],
                    ['question' => 'Why is 1080p missing for my Short?', 'answer' => 'The Short was uploaded at 720p or lower, so YouTube has no 1080p rendition to return.'],
                    ['question' => 'Can I download Shorts from a channel in bulk?', 'answer' => 'No. Each Short has its own link and is processed one at a time.'],
                    ['question' => 'Will the downloaded Short have a watermark?', 'answer' => 'YouTube does not add a watermark to Shorts, so the file matches what you see in the player.'],
                ],
            ],

            'youtube-to-mp4' => [
                'meta_title' => 'YouTube to MP4 – Download Videos in 360p to 1080p | Save-Froms',
                'meta_description' => 'Save public YouTube videos as MP4. Understand MP4 vs WEBM, which resolutions arrive as MP4, why 1080p can be video-only, and how to download on Android or iPhone.',
                'content' => <<<'HTML'
<h2>What does YouTube to MP4 mean?</h2>
<p>YouTube does not serve a single video file; it stores every upload as several renditions in two container families, MP4 (H.264 or AV1 video, AAC audio) and WEBM (VP9 video, Opus audio). "YouTube to MP4" simply means picking an MP4 rendition from that set. Save-Froms checks one public YouTube link, lists every rendition the provider returns with its container, resolution and size, and lets you choose the MP4 row that suits your device. Nothing is re-encoded, so the quality is exactly what YouTube stored. For everything about YouTube links and privacy rules, see the parent <a href="/youtube-downloader">YouTube video downloader</a> page; this page is only about getting an MP4 file.</p>
<div class="tool-steps"><div><strong>Copy a video URL</strong><span>Watch, youtu.be or Shorts link.</span></div><div><strong>Submit the link</strong><span>Paste it into the form above.</span></div><div><strong>Find the MP4 rows</strong><span>The badge on each row shows the container.</span></div><div><strong>Choose a resolution</strong><span>Balance picture detail against file size.</span></div></div>

<h2>MP4 vs WEBM: which should you download?</h2>
<table><thead><tr><th></th><th>MP4</th><th>WEBM</th></tr></thead><tbody>
<tr><td>Plays on</td><td>Every phone, TV, editor and player</td><td>Chrome, Firefox, Android, VLC; not all iPhones, TVs or editors</td></tr>
<tr><td>Codec</td><td>H.264 (sometimes AV1)</td><td>VP9 (sometimes AV1)</td></tr>
<tr><td>Size at the same resolution</td><td>About 10–30% larger</td><td>Smaller</td></tr>
<tr><td>Typically available as</td><td>360p and 720p with sound; 1080p+ often video-only</td><td>Most resolutions above 480p</td></tr>
<tr><td>Best for</td><td>Sharing, iPhone, editing, TVs</td><td>Archiving at high resolution on a computer</td></tr>
</tbody></table>
<p>If you are not sure, take MP4: it is the format that opens everywhere. Choose WEBM only when you need a resolution that is not offered as MP4 and you will play the file in a browser or VLC.</p>

<h2>Which resolutions arrive as MP4?</h2>
<ul>
<li><strong>360p MP4</strong>: always available for public videos; picture and sound in one file; about 3–6 MB per minute.</li>
<li><strong>720p MP4</strong>: available for almost every video uploaded in HD; picture and sound in one file; about 8–15 MB per minute. This is the safest high-quality choice for phones.</li>
<li><strong>1080p MP4</strong>: available for most HD uploads, but usually as a video-only stream that the provider has to combine with audio (see below); about 15–35 MB per minute.</li>
<li><strong>1440p and 2160p</strong>: usually WEBM only. If an MP4 row appears at these sizes it is AV1 and may not play on older devices.</li>
</ul>
<p>Save-Froms cannot create a genuine 1080p MP4 from a 480p upload; the rows you see are the renditions that exist on YouTube for that specific video.</p>

<h2>Why 1080p MP4 is often video-only</h2>
<p>For anything above 720p, YouTube stores picture and sound as separate streams ("adaptive" delivery) so the player can switch quality mid-stream. A plain 1080p MP4 therefore has no audio track. When you choose that row, the media provider merges the video stream with the best audio stream on demand, which is why the button can show <em>Preparing</em> for a few seconds, or longer for a long video. The merged file has sound. If the merge fails, the 720p row is a reliable fallback because it is stored as a complete file.</p>

<h2>MP4 compatibility notes</h2>
<p>H.264 MP4 plays on every device made in the last decade. The only exceptions are AV1 MP4 files at 1440p and above, which need a 2020-or-newer phone or a current desktop browser. If a downloaded MP4 plays with no sound, it was a video-only stream saved before the merge finished; analyse the link again and let the Preparing step complete. For editing in CapCut, Premiere or iMovie, MP4 at 720p or 1080p is the right import format.</p>

<h2>YouTube to MP4 on Android</h2>
<p>Copy the link from the YouTube app's Share menu, open this page in Chrome, paste and submit. Tap Download next to a row whose badge says MP4. Chrome stores it in Downloads and Google Photos picks it up automatically. On a data connection, 720p keeps a ten-minute video around 100 MB.</p>

<h2>YouTube to MP4 on iPhone</h2>
<p>Use Safari rather than the YouTube app's in-app browser. Paste the link, submit, and tap Download on an MP4 row; Safari saves to Files → Downloads. Open the file from Files and use the share sheet's <strong>Save Video</strong> to move it to Photos. Prefer MP4 over WEBM on iPhone, because the Photos app and most iOS players do not open WEBM.</p>
<div class="note"><strong>Responsible use:</strong> save only public videos you created, public-domain media, or content you are authorised to keep. A downloadable MP4 does not transfer copyright.</div>
HTML,
                'faq_items' => [
                    ['question' => 'Does every YouTube link provide MP4?', 'answer' => 'Nearly every public video returns at least a 360p and a 720p MP4. Higher resolutions depend on the upload and may be WEBM only.'],
                    ['question' => 'Is 1080p always better than 720p?', 'answer' => 'It is sharper when the upload is genuinely 1080p, but the file is roughly twice the size and needs an on-demand merge with audio. For phone screens, 720p is usually indistinguishable.'],
                    ['question' => 'Why does preparing an MP4 take time?', 'answer' => 'Resolutions above 720p are stored as separate video and audio streams, and the provider combines them when you ask for that row.'],
                    ['question' => 'Can I convert a YouTube video to MP4 at 4K?', 'answer' => '4K is normally delivered as WEBM (VP9). An MP4 row at 4K appears only when YouTube stored an AV1 version, which needs a recent device to play.'],
                    ['question' => 'Does the MP4 keep the original quality?', 'answer' => 'Yes. Save-Froms does not re-encode; you receive the rendition YouTube stored for that resolution.'],
                ],
            ],
        ];
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Batch 1 of the keyword plan (2026-10-27): every tool page gets page-specific
 * sections and FAQs so none stays under ~1,000 words, and the two YouTube pages
 * show a real result screenshot (Big Buck Bunny, a Creative Commons film).
 * Each section is written for its page only; nothing is copied between pages.
 */
class ExpandThinToolPagesAndAddResultScreenshot extends Migration
{
    const FIGURE = '<figure class="result-figure"><img src="/images/screenshots/youtube-result-big-buck-bunny.jpg" alt="Save-Froms result for a public YouTube link: MP4 rows from 144p to 1080p, WEBM at 1440p and 2160p, and MP3 audio at 48, 128 and 256 kbps with file sizes" title="Real result for a public YouTube link" width="766" height="660" loading="lazy" decoding="async"><figcaption>A real result for a public 10-minute 4K upload (Big Buck Bunny, Creative Commons): eight video rows and three audio rows, each with its size.</figcaption></figure>';

    public function up()
    {
        $now = now();

        foreach ($this->landingPages() as $slug => $page) {
            $row = DB::table('landing_pages')->where('slug', $slug)->first();
            if (!$row || strpos($row->content, $page['marker']) !== false) {
                continue;
            }
            $update = ['content' => $row->content."\n".$page['section'], 'updated_at' => $now];
            if (!empty($page['faq_items'])) {
                $update['faq_items'] = json_encode($page['faq_items'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
            if (!empty($page['intro'])) {
                $update['intro'] = $page['intro'];
            }
            DB::table('landing_pages')->where('id', $row->id)->update($update);
        }

        foreach ($this->platforms() as $slug => $platform) {
            $row = DB::table('supported_sites')->where('slug', $slug)->first();
            if (!$row || strpos($row->description, $platform['marker']) !== false) {
                continue;
            }
            DB::table('supported_sites')->where('id', $row->id)->update([
                'description' => $platform['prepend'].$row->description."\n".$platform['section'],
                'updated_at' => $now,
            ]);
        }

        $mp4 = DB::table('landing_pages')->where('slug', 'youtube-to-mp4')->first();
        if ($mp4 && strpos($mp4->content, 'result-figure') === false) {
            DB::table('landing_pages')->where('id', $mp4->id)->update([
                'content' => self::FIGURE."\n".$mp4->content,
                'updated_at' => $now,
            ]);
        }
    }

    private function platforms()
    {
        return [
            'youtube-downloader' => [
                'marker' => 'result-figure',
                'prepend' => self::FIGURE."\n",
                'section' => '',
            ],
            'tiktok-downloader' => [
                'marker' => 'Photo-mode posts and slideshows',
                'prepend' => '',
                'section' => <<<'HTML'
<h2>Every TikTok link type, and what it points to</h2>
<table><thead><tr><th>Link looks like</th><th>Where it comes from</th><th>Works here?</th></tr></thead><tbody>
<tr><td><code>tiktok.com/@name/video/7301…</code></td><td>Desktop address bar, or a short link after it redirects</td><td>Yes, this is the canonical form</td></tr>
<tr><td><code>vm.tiktok.com/ZM…</code> or <code>vt.tiktok.com/ZS…</code></td><td>Share → Copy link in the app</td><td>Yes; it redirects to the canonical form</td></tr>
<tr><td><code>tiktok.com/t/ZT…</code></td><td>Newer app share links</td><td>Yes, same redirect</td></tr>
<tr><td><code>m.tiktok.com/v/7301….html</code></td><td>Older mobile web links</td><td>Usually; paste the redirected address if not</td></tr>
<tr><td><code>tiktok.com/@name</code></td><td>Profile page</td><td>No: it is a list of videos, not one video</td></tr>
<tr><td><code>tiktok.com/music/…</code> or <code>/tag/…</code></td><td>Sound or hashtag page</td><td>No: open a specific video from it first</td></tr>
<tr><td><code>tiktok.com/@name/live</code></td><td>Live stream</td><td>No: live video is not a file until a replay is published</td></tr>
</tbody></table>
<h2>Photo-mode posts and slideshows</h2>
<p>A TikTok "photo mode" post is a set of images with a sound, not a single video. For those links the result may list the images and the audio separately, or only the audio, because there is no MP4 to return. If you need the slideshow as one video, the creator's own export is the only accurate source.</p>
<h2>Which TikTok version are you getting?</h2>
<p>TikTok stores several renditions of each clip. The result normally shows the highest one served to the web player, typically 720p or 1080p vertical (1080 × 1920). A creator who uploaded with "Allow high-quality uploads" off will have a softer 720p source, and no row can improve on it. Two rows with the same resolution but different sizes are different bitrates: the larger one keeps more detail in fast motion and text.</p>
HTML,
            ],
        ];
    }

    private function landingPages()
    {
        return [
            'youtube-mp3-downloader' => [
                'marker' => 'What the audio rows mean',
                'intro' => 'Paste one public YouTube link and pick an audio-only row. You get the sound track as a file, usually at 48, 128 or 256 kbps, without downloading the picture.',
                'section' => <<<'HTML'
<h2>What the audio rows mean</h2>
<p>For a public video the result lists each audio stream YouTube serves, labelled with its bitrate and size. For a ten-minute video that is roughly: 48 kbps about 3.7 MB, 128 kbps about 9.8 MB, 256 kbps about 9.7 MB. The last two are similar in size because YouTube stores the higher rate in a more efficient codec; choose 256 kbps for music, 128 kbps for speech, and 48 kbps only when data is scarce. The bitrate describes the stream YouTube has, not a conversion: picking a bigger number never adds detail that the upload lacked.</p>
<table><thead><tr><th>Row</th><th>Best for</th><th>Ten minutes weighs</th><th>Notes</th></tr></thead><tbody>
<tr><td>MP3 256 kbps</td><td>Music, live sessions, anything with a wide mix</td><td>About 10 MB</td><td>Highest quality YouTube serves for most uploads</td></tr>
<tr><td>MP3 128 kbps</td><td>Podcasts, talks, interviews, lectures</td><td>About 10 MB</td><td>Standard quality; speech sounds identical to 256</td></tr>
<tr><td>MP3 48 kbps</td><td>Voice memos, language drills, slow connections</td><td>About 4 MB</td><td>Noticeably flat for music</td></tr>
</tbody></table>
<h2>Download audio from a YouTube video, step by step</h2>
<ol>
<li>Open the video on YouTube and check that it plays without signing in.</li>
<li>Copy the watch or youtu.be link from Share. A playlist link, a channel link or a YouTube Music link with <code>list=</code> in it is not a single video; open one track from it and copy that.</li>
<li>Paste it above and press the button. The result shows the video rows first and the audio rows under the "Audio" heading.</li>
<li>Press Download on the bitrate you want. On Android the file lands in Downloads; on iPhone, Safari puts it in Files → Downloads.</li>
<li>To play it in a music app, move it into the Music folder on Android, or share it from Files into an app such as VLC or Documents on iPhone. Apple Music on iPhone does not import local files directly; use a Mac or PC with iTunes/Finder for that.</li>
</ol>
<h2>YouTube Music, Premium and what a link can return</h2>
<p>A YouTube Music track is also a YouTube video, so its public watch link returns the same audio rows. What a public link cannot return is anything that needs a subscription: Premium-only tracks, members-only uploads and YouTube Music's offline library. If a song only plays while you are signed in, it is not public, and the result will be empty.</p>
<h2>Audio from a long video or a live replay</h2>
<p>Long videos, such as a three-hour concert or a lecture series, work the same way but the file is proportionally larger: a three-hour 128 kbps file is about 170 MB. Live streams return audio only after the stream has ended and YouTube has processed the replay, which can take a few hours for long streams. If a replay shows video rows but no audio rows, try again later; processing is not always finished at once.</p>
<h2>Listening in a podcast app or in the car</h2>
<p>A downloaded lecture or interview is easiest to manage in a podcast app rather than a music player, because podcast apps remember your position and offer 1.25× and 1.5× speed. Pocket Casts, AntennaPod (Android) and Overcast (iPhone) all accept local files: on Android, point the app at the Downloads folder; on iPhone, open the file in Files, tap Share and choose the app. For the car, copy the MP3 to a USB stick formatted as FAT32, which almost every stereo reads, or stream it over Bluetooth from the phone. A 128 kbps file is more than enough through car speakers, so there is no need to carry the larger rows.</p>
<h2>Trimming, splitting and naming the file</h2>
<p>The file keeps the whole video's length. To keep one song from a mix or one chapter from a lecture, trim it on your device: on a computer, Audacity or FFmpeg (<code>ffmpeg -ss 00:12:00 -to 00:16:30 -i audio.mp3 -c copy part.mp3</code>) cut without re-encoding; on a phone, free editors such as Lexis Audio Editor (Android) or the Voice Memos trim tool after importing (iPhone) do the same. The file is named from the video title; rename it before adding it to a library so your music app sorts it correctly.</p>
HTML,
                'faq_items' => [
                    ['question' => 'Is the MP3 the original audio quality?', 'answer' => 'It is the audio stream YouTube itself serves, so it is as good as YouTube\'s copy. The upload\'s own quality and YouTube\'s encoding set the ceiling; a higher bitrate row never adds detail beyond that.'],
                    ['question' => 'Can I download a whole playlist as MP3?', 'answer' => 'No. Each result is for one video. Open each track from the playlist and copy its own link.'],
                    ['question' => 'Why is 256 kbps the same size as 128 kbps?', 'answer' => 'YouTube stores the higher rate in a more efficient codec, so the two files are close in size even though 256 kbps sounds fuller for music.'],
                    ['question' => 'Does the audio file include the video\'s captions or chapters?', 'answer' => 'No. Captions and chapters are separate data on YouTube and are not part of the audio stream.'],
                    ['question' => 'Can I use the audio in my own video?', 'answer' => 'Only if you own the recording, it is licensed for that use, or the creator allows it. Public playback on YouTube does not grant reuse rights.'],
                ],
            ],
            'instagram-stories-downloader' => [
                'marker' => 'Story link anatomy',
                'intro' => 'Paste the link of one live public Instagram Story or Highlight to see the video or image Instagram returns for it. Works only while the Story is still up and the account is public.',
                'section' => <<<'HTML'
<h2>Story link anatomy: which addresses work</h2>
<table><thead><tr><th>Address</th><th>What it is</th><th>Result</th></tr></thead><tbody>
<tr><td><code>instagram.com/stories/username/3245…</code></td><td>One specific Story, copied from the Story's own menu</td><td>Works while the Story is live</td></tr>
<tr><td><code>instagram.com/stories/username/</code></td><td>The account's current Story tray</td><td>Returns the first live Story only; copy the exact one you want</td></tr>
<tr><td><code>instagram.com/stories/highlights/1784…</code></td><td>A Highlight, which keeps old Stories</td><td>Works for as long as the Highlight is public</td></tr>
<tr><td><code>instagram.com/s/aGlnaGxpZ2h0…</code></td><td>A shortened share link from the app</td><td>Works after it redirects; open it once in a browser and copy the final address</td></tr>
<tr><td><code>instagram.com/username/</code></td><td>Profile page</td><td>No: a profile is not a Story</td></tr>
</tbody></table>
<h2>Get the link from the app, on Android and iPhone</h2>
<ol>
<li>Open the profile and tap the ring around the profile picture to play the Story.</li>
<li>Tap the three dots at the top right of the Story (iPhone: bottom right).</li>
<li>Choose <strong>Copy link</strong>. If the menu shows only Share to…, pick Copy link from the share sheet that opens.</li>
<li>Paste the link into the box above. If the result is empty, open the link in a private window: a login wall means the account is private or the Story has already ended.</li>
</ol>
<h2>Video Stories, photo Stories and stickers</h2>
<p>A video Story returns one MP4 of up to 60 seconds; a photo Story returns one JPG. Stickers, polls, questions, countdowns, link stickers and typed text are layers drawn over the media by the Instagram app, so they are not in the file. A music sticker's audio is included only when Instagram's public rendition carries it, which depends on the music licence for your region; many public renditions are silent for that reason.</p>
<h2>Timing matters more than for any other Instagram link</h2>
<p>A Story lives for 24 hours from the moment it was posted, not from when you saw it. If a friend sends you a Story link, it may already be close to the end of its window, so paste it as soon as you can. A Story that was added to a Highlight keeps working through the Highlight link even after the original expired, and that is the link to prefer for anything you need later.</p>
<h2>Story file sizes</h2>
<p>A 15-second video Story is usually 2 to 5 MB at 720 × 1280 or 1080 × 1920; a photo Story is 150 to 400 KB. Because the file is small, there is no quality trade-off to think about: download it on mobile data without concern, and expect the same rendition whether you check it from a phone or a computer. Instagram re-encodes every Story on upload, so even the creator's original phone recording is sharper than any downloaded copy.</p>
<h2>Highlights: the reliable way to keep a Story</h2>
<p>When a creator moves a Story into a Highlight, Instagram keeps the original media at the same quality for as long as the Highlight exists. Open the profile, tap the Highlight circle, open the exact item, and copy its link from the three-dot menu; the address starts with <code>/stories/highlights/</code>. The result for a Highlight is identical to the one for the live Story: one MP4 or one JPG, with the same limits on stickers and music. For anything you will need next week rather than today, ask for the Highlight link rather than the Story link.</p>
<h2>Desktop: Stories in a browser</h2>
<p>On instagram.com, a public account's Story plays when you click the profile picture, and the browser's address bar shows the <code>/stories/username/ID</code> address for the item on screen. Copy it from there and paste it above; this avoids the share sheet entirely and is the quickest route when you are checking several Stories from the same account. The address updates as you tap through the tray, so copy it while the Story you want is showing.</p>
<h2>What to do when the Story is gone</h2>
<p>Nothing on the web can retrieve an expired Story from its link. If it was your own Story, the Archive in your Instagram settings keeps every Story you posted, and you can download from there at full quality. If it was someone else's, ask whether they added it to a Highlight or can send you the original from their archive.</p>
HTML,
                'faq_items' => [
                    ['question' => 'Can I download an Instagram Story anonymously?', 'answer' => 'A public-link check does not use an Instagram account, so it does not add you to the viewer list. It only works for public accounts.'],
                    ['question' => 'How long does the Story link work?', 'answer' => 'Until the Story ends, 24 hours after it was posted. Highlight links work for as long as the Highlight stays public.'],
                    ['question' => 'Why is my Story download a picture, not a video?', 'answer' => 'The link pointed to a photo Story, or to a different Story than you expected. Copy the link from the exact Story again.'],
                    ['question' => 'Can I download Stories from a private account I follow?', 'answer' => 'No. The tool cannot use your login. Private Stories are visible only inside your own Instagram session.'],
                    ['question' => 'Does the download include the music?', 'answer' => 'Only when Instagram\'s public rendition carries the audio. Licensed music is often muted in that rendition.'],
                ],
            ],
            'tiktok-mp3-downloader' => [
                'marker' => 'Original sound, added sound and what the audio row contains',
                'intro' => 'Paste one public TikTok link and choose the audio row to get the clip\'s sound as an MP3. Works with full tiktok.com links and vm/vt short links.',
                'section' => <<<'HTML'
<h2>Original sound, added sound and what the audio row contains</h2>
<p>Every TikTok has a sound attached. When the creator recorded their own voice or music, TikTok labels it "original sound – username". When they picked a track from the library, the clip carries that track plus whatever the creator said over it. The audio row returned for a link is the clip's mixed sound track, exactly as you hear it in the app: voice, effects and music together. It is not the clean library track, and it is not the creator's voice on its own.</p>
<table><thead><tr><th>What you want</th><th>What the audio row gives you</th><th>Better source</th></tr></thead><tbody>
<tr><td>The creator's voice-over or comedy audio</td><td>Exactly that, with any background music mixed in</td><td>—</td></tr>
<tr><td>The clean music track</td><td>The mix from the clip, not the clean track</td><td>The artist's release on a music service</td></tr>
<tr><td>A sound to reuse in your own TikTok</td><td>A file you would have to re-upload</td><td>Use "Use this sound" in the app, which credits the owner</td></tr>
<tr><td>A podcast or interview clip</td><td>The spoken audio</td><td>—</td></tr>
</tbody></table>
<h2>Bitrate and size of a TikTok MP3</h2>
<p>TikTok clips carry AAC audio at roughly 64 to 128 kbps, so the MP3 row is usually labelled 128 kbps and a 60-second clip is about 1 MB. A three-minute clip is about 3 MB. There is only one audio stream per clip, which is why the result shows a single audio row rather than the bitrate menu you see for YouTube.</p>
<h2>Download the sound on a phone</h2>
<ol>
<li>In the TikTok app, tap Share on the clip and choose Copy link.</li>
<li>Open Chrome (Android) or Safari (iPhone) and paste the link above. The in-app browser inside TikTok often blocks the download, so switch apps first.</li>
<li>Press Download on the MP3 row. Android saves to Downloads; iPhone saves to Files → Downloads.</li>
<li>To set it as a ringtone on Android, move it to the Ringtones folder and pick it in Settings → Sound. On iPhone, ringtones must go through GarageBand or a computer; a plain MP3 in Files will not appear in the ringtone list.</li>
</ol>
<h2>Photo-mode posts, duets and stitches</h2>
<p>A photo-mode post has a sound but no video, so its result may show only the audio row, which is handy when the sound is what you wanted. Duets and stitches contain the audio of both clips mixed together; there is no way to separate the two halves from the file. Live streams are not clips and return nothing until a replay is posted.</p>
<h2>Naming and organising downloaded sounds</h2>
<p>The MP3 is named after the clip's caption, which is often a string of hashtags. Rename it to the sound's title and the creator's username straight after downloading, before it disappears among other files, and keep a note of the clip link in case you need to credit the creator or check the licence later.</p>
<h2>Finding the clip that a sound came from</h2>
<p>If you only know the sound, not the clip, open the sound's page in TikTok (tap the spinning disc or the sound name under a video). The page lists every public clip that uses it, with the original at the top marked "Original". Open that clip and copy its link: its audio row is the cleanest version of the sound, because later clips mix their own voice-overs on top. Sound pages themselves (<code>tiktok.com/music/…</code>) cannot be pasted, since they are lists rather than one clip.</p>
<h2>Speech-only clips and voice notes</h2>
<p>Interviews, lessons and story-time clips are where a TikTok MP3 is most useful: the sound carries everything and the picture adds little. For those, the 128 kbps row is more than enough and a five-minute clip is about 5 MB. Podcast apps on Android and iPhone can play local files and remember your position, which suits longer multi-part series better than a music player.</p>
<h2>Reusing a TikTok sound legally</h2>
<p>Library music on TikTok is licensed for use inside TikTok. Taking the MP3 and putting it under a YouTube video, an Instagram Reel or an advert is a different use that the licence does not cover. Original sounds belong to the creator who recorded them. Reuse either one outside TikTok only with permission, and give credit when the creator asks for it.</p>
HTML,
                'faq_items' => [
                    ['question' => 'Why does the TikTok MP3 contain talking over the music?', 'answer' => 'The audio row is the clip\'s mixed sound, exactly as it plays in the app. The clean track exists only on the artist\'s release.'],
                    ['question' => 'Why is there only one audio row?', 'answer' => 'TikTok stores a single audio stream per clip, unlike YouTube, which serves several bitrates.'],
                    ['question' => 'Can I get the sound from a private TikTok?', 'answer' => 'No. Only public clips can be checked. A private or Friends-only clip returns nothing.'],
                    ['question' => 'Does the MP3 keep the video\'s captions or text?', 'answer' => 'No. On-screen text and auto-captions are part of the picture, not the sound.'],
                    ['question' => 'Can I use a TikTok MP3 as a ringtone?', 'answer' => 'On Android, yes, from the Ringtones folder. On iPhone, ringtones need GarageBand or a computer.'],
                ],
            ],
            'tiktok-mp4-downloader' => [
                'marker' => 'Resolution, frame rate and size of a TikTok MP4',
                'section' => <<<'HTML'
<h2>Resolution, frame rate and size of a TikTok MP4</h2>
<table><thead><tr><th>Rendition</th><th>Pixels</th><th>Typical bitrate</th><th>One minute weighs</th><th>When TikTok serves it</th></tr></thead><tbody>
<tr><td>540p</td><td>540 × 960</td><td>1 to 1.5 Mbps</td><td>8 to 11 MB</td><td>Older uploads, slow connections</td></tr>
<tr><td>720p</td><td>720 × 1280</td><td>1.5 to 2.5 Mbps</td><td>11 to 19 MB</td><td>Most clips</td></tr>
<tr><td>1080p</td><td>1080 × 1920</td><td>2.5 to 5 Mbps</td><td>19 to 38 MB</td><td>Uploads made with "Allow high-quality uploads" on</td></tr>
<tr><td>1080p60</td><td>1080 × 1920, 60 fps</td><td>4 to 8 Mbps</td><td>30 to 60 MB</td><td>Phone footage shot at 60 fps and uploaded in HD</td></tr>
</tbody></table>
<p>The rows for your link show the renditions TikTok returned at that moment. Vertical clips are listed by their height, so "1080p" is 1080 pixels wide and 1920 tall. Landscape clips, which are rare on TikTok, are letterboxed inside the vertical frame.</p>
<h2>Why the MP4 looks softer than the app</h2>
<p>The TikTok app streams a rendition matched to your screen and sharpens it during playback. A downloaded MP4 is the stored file without that processing, so a 720p source can look slightly softer on a large phone. If the result offers a 1080p row, use it for anything you plan to edit or show on a tablet or TV. If only 720p appears, the creator uploaded at 720p and nothing can raise it.</p>
<h2>Sending a TikTok MP4 through messaging apps</h2>
<p>WhatsApp, Messenger and iMessage compress videos again when you send them, and some apps cap the size (WhatsApp at about 16 MB for an inline video on some platforms). For a clip you want to keep sharp, send it as a document or file attachment instead of as a video, or share the original TikTok link so the receiver can download the same MP4 themselves.</p>
<h2>Saving a TikTok MP4 to Photos or the gallery</h2>
<p>On Android, Chrome saves to the Download folder and the gallery app indexes it within a few seconds; if it does not appear, open Files, long-press the MP4 and move it to DCIM. On iPhone, Safari stores it in Files → Downloads. Open the file, tap Share and choose Save Video to put it in Photos, where it can be edited or shared like any other clip.</p>
<h2>Checking the rendition before you download</h2>
<p>The row's size tells you more than its label. Divide the size by the clip's length: about 0.3 MB per second means a well-encoded 1080p clip, 0.15 to 0.25 MB per second is 720p or a low-bitrate 1080p, and under 0.1 MB per second is a soft 540p source. If two links from the same creator give very different figures, the smaller one was probably uploaded from a lower-quality source or re-posted from a download, which is common with reuploaded clips. Choosing the larger of two rows costs a few megabytes and avoids a second visit when the first file looks blurry on a bigger screen.</p>
<h2>Keeping a TikTok for editing</h2>
<p>If the clip is going into a video editor, take the largest MP4 row, keep the original file untouched, and edit a copy. TikTok MP4s are H.264 with AAC audio, so CapCut, Premiere, DaVinci Resolve and iMovie open them directly without conversion. The vertical 9:16 frame fits a Reel or Short as is; for a landscape project, crop or add a blurred background rather than stretching the frame.</p>
<h2>When a TikTok MP4 row is missing</h2>
<ul>
<li><strong>Private, Friends-only or deleted clip:</strong> nothing is returned; the link must be public.</li>
<li><strong>Photo-mode post:</strong> there is no MP4; the result lists images and the audio instead.</li>
<li><strong>Age-restricted clip:</strong> TikTok hides these from signed-out viewers, so no public rendition exists.</li>
<li><strong>Region-limited clip:</strong> a creator can restrict countries; the result follows TikTok's rule for the server's region.</li>
<li><strong>Just-posted clip:</strong> renditions are still being generated; try again in a few minutes.</li>
</ul>
HTML,
                'faq_items' => [
                    ['question' => 'What resolution will my TikTok MP4 be?', 'answer' => 'Usually 720p or 1080p vertical, depending on how the creator uploaded. The result lists the rows TikTok returned for that link.'],
                    ['question' => 'Why are there two MP4 rows with the same resolution?', 'answer' => 'They are different bitrates. The larger file keeps more detail in motion and text.'],
                    ['question' => 'Does the MP4 include the TikTok watermark?', 'answer' => 'Usually not: the row comes from the web player rendition, which has no overlay. Some clips are served only with the overlay.'],
                    ['question' => 'How big is a one-minute TikTok MP4?', 'answer' => 'About 11 to 19 MB at 720p and 19 to 38 MB at 1080p.'],
                    ['question' => 'Can I download a TikTok live stream?', 'answer' => 'Not while it is live. Only a replay the creator has published can be checked.'],
                ],
            ],
            'facebook-reels-downloader' => [
                'marker' => 'Facebook Reel links: the three shapes you will meet',
                'intro' => 'Paste one public Facebook Reel link to see the MP4 renditions Facebook returns for it, usually SD and HD, and save the one you are allowed to keep.',
                'section' => <<<'HTML'
<h2>Facebook Reel links: the three shapes you will meet</h2>
<table><thead><tr><th>Address</th><th>Where it comes from</th><th>What to do</th></tr></thead><tbody>
<tr><td><code>facebook.com/reel/1234567890</code></td><td>Desktop address bar, Share → Copy link</td><td>Paste as is</td></tr>
<tr><td><code>fb.watch/abc123/</code></td><td>Share from the mobile app</td><td>Paste as is; it redirects to the Reel</td></tr>
<tr><td><code>facebook.com/share/r/abc123/</code></td><td>Newer app share links</td><td>Paste as is; if the result is empty, open it once in a browser and copy the final <code>/reel/</code> address</td></tr>
<tr><td><code>facebook.com/username/reels/</code></td><td>Profile or Page Reels tab</td><td>No: open one Reel from it and copy that link</td></tr>
<tr><td><code>facebook.com/groups/…/posts/…</code></td><td>A Reel shared inside a group</td><td>Works only if the group is public; otherwise it is not a public link</td></tr>
</tbody></table>
<h2>SD and HD: what Facebook returns</h2>
<p>Facebook stores two renditions of most videos: SD (around 360p to 540p) and HD (720p or 1080p). The result shows both when they exist. A Reel uploaded from a phone with "Upload in HD" switched off in Facebook's settings exists only in SD, and that is all any tool can return. HD Reels are usually 15 to 40 MB per minute; SD about 5 to 10 MB.</p>
<h2>Page Reels, profile Reels and audience settings</h2>
<p>A Reel posted by a Page is public by default. A Reel from a personal profile follows that post's audience: Public works, Friends or Only me does not, even if you are friends with the person, because the check uses no Facebook account. The quickest test is to open the link in a private window: if Facebook shows the Reel without asking you to log in, it is public.</p>
<h2>Why the sound is sometimes missing</h2>
<p>Facebook mutes licensed music in the public rendition of some Reels in some countries, so the MP4 can have silence where the app plays a song. The creator's voice and original audio are normally kept. If sound matters and the result is silent, there is no other rendition to fetch; the creator's own export is the only source with the track.</p>
<h2>HD on a slow connection</h2>
<p>The HD row of a one-minute Reel can be 40 MB, which takes a while on a weak mobile signal and can fail part-way, leaving a file that will not play. On a slow connection, download SD first to confirm the Reel is the right one, then fetch HD on Wi-Fi. A file that stops early can be deleted and re-downloaded from the same result page while the temporary link is still valid; after that, paste the Reel link again.</p>
<h2>Reels inside Facebook Watch and Marketplace</h2>
<p>Facebook shows Reels in several places with the same underlying video: the Reels tab, Watch, a Page's feed and occasionally a Marketplace listing. Whichever surface you copy from, the Share link resolves to the same Reel ID and returns the same renditions. Reels embedded on an outside website point back to Facebook too; right-click the embedded player and choose Copy link, or click the Facebook logo in the player to open the Reel and copy its address.</p>
<h2>Cross-posted Reels from Instagram</h2>
<p>Many Facebook Reels were posted first on Instagram and cross-posted. Facebook keeps its own copy, so the Facebook link works on its own and returns Facebook's renditions, which can be a different size from Instagram's. If the Facebook copy is SD only, the Instagram original sometimes exists at 1080p; search the creator's Instagram and use the <a href="/instagram-reels-downloader">Instagram Reels Downloader</a> with that link.</p>
<h2>Download a Facebook Reel on Android, iPhone or desktop</h2>
<ol>
<li>Open the Reel in the Facebook app or on facebook.com.</li>
<li>Tap Share, then Copy link.</li>
<li>Paste it above and press the button. On a phone, use Chrome or Safari rather than the browser inside the Facebook app.</li>
<li>Choose HD when it is offered and you have the space; SD for quick sharing.</li>
<li>Find the file in Downloads (Android, desktop) or Files → Downloads (iPhone), then save it to Photos if you want it in the gallery.</li>
</ol>
HTML,
                'faq_items' => [
                    ['question' => 'Why does the result show only SD for a Facebook Reel?', 'answer' => 'The creator uploaded without HD, so Facebook has only an SD copy. No tool can produce HD from it.'],
                    ['question' => 'Can I download a Reel from a private group?', 'answer' => 'No. Only Reels in public groups, on Pages or on public profile posts can be checked.'],
                    ['question' => 'Does an fb.watch link work?', 'answer' => 'Yes. It redirects to the Reel and the result is the same as for the full link.'],
                    ['question' => 'Why is the downloaded Reel silent?', 'answer' => 'Facebook muted licensed music in the public rendition for your region. The creator\'s own file is the only version with the track.'],
                    ['question' => 'Does downloading tell the creator?', 'answer' => 'No. The check uses no Facebook account and leaves no trace on the post.'],
                ],
            ],
            'instagram-reels-downloader' => [
                'marker' => 'Reel link, post link and share link',
                'section' => <<<'HTML'
<h2>Reel link, post link and share link</h2>
<table><thead><tr><th>Address</th><th>Meaning</th><th>Result</th></tr></thead><tbody>
<tr><td><code>instagram.com/reel/CxYz…/</code></td><td>A Reel, copied from Share → Copy link</td><td>The Reel's MP4</td></tr>
<tr><td><code>instagram.com/p/CxYz…/</code></td><td>A feed post, which may be a video, a photo or a carousel</td><td>The video's MP4 if it is a video post; a Reel also opens under /p/ and works</td></tr>
<tr><td><code>instagram.com/reels/CxYz…/</code></td><td>A Reel opened from the Reels tab</td><td>Same as /reel/</td></tr>
<tr><td><code>instagram.com/username/reels/</code></td><td>The account's Reels grid</td><td>No: open one Reel and copy its link</td></tr>
<tr><td><code>instagram.com/reel/CxYz…/?igsh=…</code></td><td>A share link with tracking</td><td>Works; the tracking part is ignored</td></tr>
</tbody></table>
<h2>What quality Instagram serves for a Reel</h2>
<p>Instagram keeps one public rendition per Reel, usually 720 × 1280 or 1080 × 1920 at 2 to 4 Mbps, so a 30-second Reel is about 5 to 15 MB. There is no quality menu because there is nothing to choose between. Reels uploaded with "Upload at highest quality" on, under Settings → Data usage and media quality, are the ones that come back at 1080p; the rest are 720p.</p>
<h2>Collabs, remixes and Reels with original audio</h2>
<p>A collab Reel appears on two profiles but is one post, so either account's link returns the same file. A remix contains both the original clip and the new one in a single video, which is what the result returns. Reels that use a licensed track may come back silent in some countries, because Instagram's public rendition drops the music where the licence does not apply; a Reel with original audio keeps its sound.</p>
<h2>Download a Reel on Android, iPhone or desktop</h2>
<ol>
<li>Open the Reel and tap the paper-plane Share icon.</li>
<li>Choose Copy link.</li>
<li>Switch to Chrome or Safari and paste the link above. Instagram's in-app browser often refuses to save files.</li>
<li>Press Download. Android stores it in Downloads; iPhone in Files → Downloads, from where Share → Save Video moves it to Photos.</li>
</ol>
<h2>Reels that are longer than 90 seconds</h2>
<p>Instagram now allows Reels up to three minutes, and the result for a long Reel is a single MP4 of the full length, typically 30 to 60 MB at 1080p. Nothing changes in the steps; only the download takes longer on mobile data.</p>
<h2>Why a Reel link returns nothing</h2>
<ul>
<li><strong>Private account:</strong> the Reel is visible only to approved followers inside their session.</li>
<li><strong>Deleted or archived Reel:</strong> Instagram returns an error page instead of media.</li>
<li><strong>Age-gated or restricted Reel:</strong> hidden from signed-out viewers, so there is no public rendition.</li>
<li><strong>Grid or profile link:</strong> it lists many posts; copy one Reel's link instead.</li>
</ul>
HTML,
                'faq_items' => [
                    ['question' => 'Can I download a Reel from a private Instagram account?', 'answer' => 'No. Private and login-protected posts cannot be accessed through the public-link workflow; the tool never uses your login.'],
                    ['question' => 'Why is there only one quality for a Reel?', 'answer' => 'Instagram stores a single public rendition per Reel, 720p or 1080p depending on the upload setting.'],
                    ['question' => 'Does the Reel download include the music?', 'answer' => 'Original audio, yes. Licensed tracks are sometimes muted in the public rendition for your region.'],
                    ['question' => 'Will the creator know I downloaded their Reel?', 'answer' => 'No. The check uses no Instagram account, so nothing shows in their insights.'],
                    ['question' => 'Can I download a Reel from a /p/ link?', 'answer' => 'Yes. A Reel opened as a post uses the same ID and returns the same MP4.'],
                ],
            ],
        ];
    }

    public function down()
    {
        // Content expansions are forward-only.
    }
}

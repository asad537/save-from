<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLandingPagesTable extends Migration
{
    public function up()
    {
        Schema::create('landing_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('eyebrow', 100)->nullable();
            $table->string('heading');
            $table->text('intro');
            $table->longText('content');
            $table->string('meta_title', 70);
            $table->string('meta_description', 180);
            $table->longText('faq_items')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });

        $now = now();
        foreach ($this->pages() as $page) {
            $page['faq_items'] = json_encode($page['faq_items'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $page['is_active'] = true;
            $page['created_at'] = $now;
            $page['updated_at'] = $now;
            DB::table('landing_pages')->insert($page);
        }
    }

    private function pages()
    {
        return [
            [
                'title' => 'YouTube Shorts Downloader',
                'slug' => 'youtube-shorts-downloader',
                'eyebrow' => 'YouTube Shorts Tool',
                'heading' => 'Download [YouTube Shorts] Online',
                'intro' => 'Paste a complete public YouTube Shorts link to check the video formats and qualities currently available for that Short.',
                'meta_title' => 'YouTube Shorts Downloader Online | Save-Froms',
                'meta_description' => 'Download public YouTube Shorts in the video formats available for the source. Paste a Shorts URL, compare quality and file size, and save responsibly.',
                'sort_order' => 1,
                'content' => '<h2>Save a public YouTube Short from its link</h2><p>YouTube Shorts are vertical, short-form videos, but their links can be processed in the same browser workflow as other public YouTube videos. Copy the address of the individual Short—not the channel, Shorts feed, playlist or search page—and paste it into the form above. Save-Froms checks the source and displays the formats returned by the media provider.</p><p>The result can include more than one resolution. Review the quality and estimated file size instead of assuming that the largest option is always best. A compact version is useful for messaging and limited storage, while a higher resolution is better suited to larger screens when the source provides it.</p><div class="tool-steps"><div><strong>Open the Short</strong><span>Choose one public YouTube Short.</span></div><div><strong>Copy its URL</strong><span>Use Share or the browser address bar.</span></div><div><strong>Paste the link</strong><span>Submit the complete URL above.</span></div><div><strong>Select a file</strong><span>Compare format, quality and size.</span></div></div><h2>Which YouTube Shorts URLs work?</h2><p>A typical link contains <strong>youtube.com/shorts/</strong> followed by the video identifier. A standard watch URL or a youtu.be share link can also identify the same public video. Tracking parameters after the video identifier normally do not change the media itself.</p><div class="note"><strong>Availability matters:</strong> private, deleted, age-restricted, region-restricted, members-only or live content may not return a file. Only download a Short you own or have permission to use.</div><h2>Shorts quality and orientation</h2><p>Most Shorts use a portrait frame. Downloading the file does not turn it into landscape video or create detail beyond the original upload. The result page shows only the qualities currently returned for that source, so older or heavily compressed Shorts may have fewer choices.</p><h2>Use on Android, iPhone and desktop</h2><p>The tool runs in a modern browser without a Save-Froms account. On a phone, the downloaded file usually appears in the browser download list or Files app. On Windows and macOS, it normally appears in the browser download folder unless you selected another location.</p>',
                'faq_items' => [
                    ['question' => 'Can I use a regular YouTube link instead of a Shorts link?', 'answer' => 'Yes. If the URL identifies one public video, a watch URL or youtu.be share link can normally be checked too.'],
                    ['question' => 'Why is a quality missing from my Short?', 'answer' => 'The available qualities depend on the original upload and the resources returned for that source.'],
                    ['question' => 'Can private Shorts be downloaded?', 'answer' => 'No. Save-Froms cannot bypass private, login-only or members-only access controls.'],
                ],
            ],
            [
                'title' => 'YouTube to MP4',
                'slug' => 'youtube-to-mp4',
                'eyebrow' => 'YouTube Video Format',
                'heading' => 'Download YouTube Videos as [MP4]',
                'intro' => 'Check a complete public YouTube URL and choose an MP4 option when that format is available for the source video.',
                'meta_title' => 'YouTube to MP4 Downloader Online | Save-Froms',
                'meta_description' => 'Download public YouTube videos as MP4 when available. Compare resolution and estimated file size in your browser before choosing a file.',
                'sort_order' => 2,
                'content' => '<h2>What does YouTube to MP4 mean?</h2><p>MP4 is a widely supported video container used by phones, tablets, computers, televisions and editing applications. This page does not promise to convert every video. Instead, Save-Froms checks one public YouTube link and lists the MP4 resources currently available from the provider. If MP4 is not returned for a particular quality, choose another listed format or resolution.</p><div class="tool-steps"><div><strong>Copy a video URL</strong><span>Use one complete public YouTube link.</span></div><div><strong>Submit the link</strong><span>Paste it into the downloader form.</span></div><div><strong>Find MP4</strong><span>Review the returned video rows.</span></div><div><strong>Choose quality</strong><span>Balance picture detail and file size.</span></div></div><h2>Choosing an MP4 resolution</h2><p>Resolution describes the pixel dimensions of the picture. 360p or 480p can be practical for small screens and slower connections. 720p usually offers a useful balance between clarity and size. 1080p and higher may look sharper, but they generally require more storage and bandwidth and may be provided as video-only streams.</p><p>Check the actual rows shown after analysis. The source determines the choices; Save-Froms cannot create a genuine HD version from an SD upload.</p><h2>Video-only and combined MP4 files</h2><p>YouTube sometimes delivers high-resolution picture and audio as separate streams. A returned MP4 can therefore be video-only, while lower resolutions may contain both picture and sound. If a provider must prepare or combine a file, processing can take longer and a temporary provider failure can occur.</p><div class="note"><strong>Responsible use:</strong> use this workflow only for public videos you created, public-domain media, or content you are authorized to save. Public access alone does not transfer copyright.</div><h2>MP4 playback compatibility</h2><p>MP4 is broadly compatible, but playback still depends on the video and audio codecs inside the file. Keep your browser and media player updated. If one returned format does not play on your device, try another available MP4 resolution or a compatible WEBM player.</p>',
                'faq_items' => [
                    ['question' => 'Does every YouTube link provide MP4?', 'answer' => 'No. Formats depend on the source and media provider, so MP4 is shown only when it is available.'],
                    ['question' => 'Is 1080p always better?', 'answer' => 'It is sharper when the source is genuine 1080p, but the file is usually larger and may not include audio in the same stream.'],
                    ['question' => 'Why does preparing an MP4 take time?', 'answer' => 'Some qualities require provider-side processing or separate video and audio handling before the file is ready.'],
                ],
            ],
            [
                'title' => 'YouTube Music and MP3 Downloader',
                'slug' => 'youtube-mp3-downloader',
                'eyebrow' => 'YouTube Audio Options',
                'heading' => 'Download Available YouTube [Audio]',
                'intro' => 'Paste a public YouTube video URL and review the audio-only options returned for that source, including MP3-labelled choices when available.',
                'meta_title' => 'YouTube Music & MP3 Downloader | Save-Froms',
                'meta_description' => 'Check a public YouTube URL for available audio-only formats. Compare MP3 quality and file size before downloading permitted music, podcasts or lectures.',
                'sort_order' => 3,
                'content' => '<h2>Audio-only options from a YouTube link</h2><p>When you only need the sound from a permitted video, an audio-only file can use less storage than the full picture-and-sound version. Save-Froms checks the public source and displays the audio resources returned by the provider. These may be labelled MP3 with a bitrate such as 48, 128 or 256 Kbps.</p><p>The label describes the available output, not an improvement to the original recording. Increasing the bitrate cannot restore detail already lost through compression or a low-quality upload.</p><div class="tool-steps"><div><strong>Open the source</strong><span>Select one public YouTube video.</span></div><div><strong>Copy its link</strong><span>Use Share or copy the address.</span></div><div><strong>Check formats</strong><span>Paste the URL and submit it.</span></div><div><strong>Select audio</strong><span>Choose an available bitrate.</span></div></div><h2>How should you choose an audio bitrate?</h2><p>A lower bitrate normally creates a smaller file and may be adequate for speech. A higher bitrate can preserve more detail for music, but also consumes more storage. Compare the estimated size and available quality in the results rather than selecting a value by name alone.</p><h2>Music, podcasts and lectures</h2><p>Audio-only downloads can be convenient for your own recordings, open-licensed lessons, permitted podcasts and public-domain material. They are not a licence to copy commercial music. The creator, publisher and source platform can retain rights even when a video is publicly viewable.</p><div class="note"><strong>Access limits:</strong> private, deleted, paid, age-gated, region-blocked and live links may not be processed. Save-Froms cannot bypass account or rights restrictions.</div><h2>Listening on different devices</h2><p>Common audio files work on modern Android, iPhone, Windows and macOS players. If your device cannot open a returned file, update the player or select another available format. The website does not store a personal music library or synchronize downloads between devices.</p>',
                'faq_items' => [
                    ['question' => 'Is an MP3 option available for every YouTube video?', 'answer' => 'No. Audio choices depend on the source and the formats returned by the media provider.'],
                    ['question' => 'Which bitrate should I choose for speech?', 'answer' => 'A smaller bitrate may be sufficient for speech, while music often benefits from a higher available bitrate.'],
                    ['question' => 'Can I download copyrighted songs?', 'answer' => 'Download only audio you own, public-domain material, or content you have permission to save.'],
                ],
            ],
            [
                'title' => 'Instagram Reels Downloader',
                'slug' => 'instagram-reels-downloader',
                'eyebrow' => 'Instagram Reels Tool',
                'heading' => 'Download Public [Instagram Reels]',
                'intro' => 'Use the complete link of an individual public Reel to check the video formats currently available for permitted offline use.',
                'meta_title' => 'Instagram Reels Downloader Online | Save-Froms',
                'meta_description' => 'Download publicly accessible Instagram Reels from a complete Reel URL. Review the available video format and file size without installing an app.',
                'sort_order' => 4,
                'content' => '<h2>Use the link to an individual public Reel</h2><p>An Instagram profile, Explore page, hashtag or audio page does not identify one downloadable video. Open the Reel itself, use the Share menu and copy its complete URL. Paste that address above so Save-Froms can ask the provider for the resources associated with that specific post.</p><div class="tool-steps"><div><strong>Open a Reel</strong><span>Choose one publicly accessible post.</span></div><div><strong>Copy the URL</strong><span>Use Copy link in the Share menu.</span></div><div><strong>Paste and check</strong><span>Submit the full Reel address.</span></div><div><strong>Save a format</strong><span>Choose from the returned options.</span></div></div><h2>Why some Reels cannot be processed</h2><p>Instagram access can change when an account becomes private, a creator removes a post, a login gate appears, or regional and age restrictions apply. A Reel that opens inside your signed-in app is not necessarily publicly accessible to an external provider.</p><p>Try opening the copied link in a private browser window. If Instagram asks for an account before showing the Reel, the downloader may not be able to access it.</p><h2>Quality and file size for vertical video</h2><p>Reels are designed primarily for phone screens. The provider may return one source-quality file or several choices. Selecting a larger file does not guarantee extra detail, especially when Instagram has already compressed the upload. Compare the displayed size and quality before downloading.</p><div class="note"><strong>Creator rights:</strong> save only a Reel you created or have permission to download. Do not remove attribution or reuse another creator’s work without authorization.</div><h2>No account required on Save-Froms</h2><p>The browser workflow does not require a Save-Froms account or application. It also does not log in to Instagram on your behalf. This separation protects platform privacy controls and means private-account Reels are outside the supported workflow.</p>',
                'faq_items' => [
                    ['question' => 'Can I download a Reel from a private Instagram account?', 'answer' => 'No. Private and login-protected posts cannot be accessed through the public-link workflow.'],
                    ['question' => 'Does Save-Froms remove the Reel watermark?', 'answer' => 'No watermark result is guaranteed; the file depends on the version returned by the media provider.'],
                    ['question' => 'Why does a Reel open in my app but fail here?', 'answer' => 'Your app may be using a signed-in session, while the external provider can access only publicly available resources.'],
                ],
            ],
            [
                'title' => 'Instagram Stories Downloader',
                'slug' => 'instagram-stories-downloader',
                'eyebrow' => 'Instagram Story Links',
                'heading' => 'Check Public [Instagram Stories]',
                'intro' => 'Paste a complete, currently accessible public Story link to see whether the provider can return downloadable media for it.',
                'meta_title' => 'Instagram Stories Downloader Guide | Save-Froms',
                'meta_description' => 'Check a currently accessible public Instagram Story link for available media. Learn why expired, private and login-only Stories may not work.',
                'sort_order' => 5,
                'content' => '<h2>Story links are temporary</h2><p>Instagram Stories normally disappear from public Story playback after a limited period unless the creator saves them as Highlights. For that reason, a copied Story URL can expire even when it worked earlier. Save-Froms can only check a link while the underlying public media remains accessible to the provider.</p><div class="tool-steps"><div><strong>Open the Story</strong><span>Confirm it is currently public.</span></div><div><strong>Copy its link</strong><span>Use the available Share controls.</span></div><div><strong>Submit quickly</strong><span>Paste the complete Story URL.</span></div><div><strong>Review results</strong><span>Save only an authorized file.</span></div></div><h2>Public Stories, private Stories and Highlights</h2><p>A Story shared by a private account or limited to Close Friends is not a public source. Save-Froms cannot use your Instagram login, read a private session or bypass an audience setting. A public Highlight may remain accessible for longer, but its availability still depends on the creator and platform.</p><h2>Why a Story URL may return no media</h2><ul><li>The Story has expired or was deleted.</li><li>The account became private.</li><li>The link opens a Story tray rather than one media item.</li><li>Instagram requires a login or consent screen.</li><li>The provider does not support the media type returned for that Story.</li></ul><div class="note"><strong>Privacy reminder:</strong> temporary content is not permission to archive or redistribute someone else’s Story. Download only your own media or content the owner has authorized you to save.</div><h2>Mobile and desktop use</h2><p>You can paste a valid Story URL from Android, iPhone or a desktop browser. The final file location is controlled by your browser. If the Story is no longer public, changing devices will not restore access.</p>',
                'faq_items' => [
                    ['question' => 'Can an expired Instagram Story be downloaded?', 'answer' => 'Usually not. Once the source is no longer publicly available, the provider has no active media to return.'],
                    ['question' => 'Do Close Friends Stories work?', 'answer' => 'No. Close Friends and private-account Stories are restricted content.'],
                    ['question' => 'Are Instagram Highlights supported?', 'answer' => 'A public Highlight may work if its individual media is accessible and supported by the provider, but availability is not guaranteed.'],
                ],
            ],
            [
                'title' => 'TikTok MP3 Downloader',
                'slug' => 'tiktok-mp3-downloader',
                'eyebrow' => 'TikTok Audio Options',
                'heading' => 'Download Available TikTok [MP3 Audio]',
                'intro' => 'Check a complete public TikTok video link and choose an audio-only option when the provider returns one for that source.',
                'meta_title' => 'TikTok MP3 Downloader Online | Save-Froms',
                'meta_description' => 'Check public TikTok videos for available MP3 audio options. Compare bitrate and file size before saving permitted sounds in your browser.',
                'sort_order' => 6,
                'content' => '<h2>Extract the available audio from a public TikTok</h2><p>An audio-only result can be useful for your own voice clips, permitted sounds, interviews and spoken notes. Paste the URL of one public TikTok video. If the provider returns an MP3-labelled resource, it will appear in the Music section alongside its bitrate and estimated file size.</p><div class="tool-steps"><div><strong>Choose a video</strong><span>Open one public TikTok post.</span></div><div><strong>Copy its link</strong><span>Use Share and Copy link.</span></div><div><strong>Check options</strong><span>Paste and submit the complete URL.</span></div><div><strong>Select audio</strong><span>Choose an available MP3 row.</span></div></div><h2>Original sound and licensed music</h2><p>A TikTok post can combine a creator’s voice, effects and licensed music. The presence of a public audio stream does not give permission to republish or commercially use it. Confirm the rights before downloading, editing or sharing the sound outside the platform.</p><h2>Bitrate does not create missing quality</h2><p>A higher displayed bitrate generally means a larger file, but it cannot reconstruct frequencies removed from the source. For speech, a smaller available option may be adequate. For music you own or are licensed to use, compare the returned choices and listen on your target device.</p><div class="note"><strong>Source limitations:</strong> private profiles, removed videos, photo posts, live streams, age gates and regional restrictions may return no audio option.</div><h2>Short links and complete TikTok URLs</h2><p>The mobile app often provides a shortened share URL. If it fails, open that link in a browser, wait for it to redirect, and copy the final TikTok video address. Profile and hashtag pages do not identify one audio source.</p>',
                'faq_items' => [
                    ['question' => 'Does every TikTok video provide MP3?', 'answer' => 'No. MP3 appears only when the provider returns an audio-only option for the submitted source.'],
                    ['question' => 'Can a photo post be downloaded as MP3?', 'answer' => 'Not necessarily. Photo posts and unsupported media types may not provide an audio resource.'],
                    ['question' => 'Which MP3 bitrate should I choose?', 'answer' => 'Choose based on the returned file size and your needs; higher bitrate usually uses more storage.'],
                ],
            ],
            [
                'title' => 'TikTok MP4 Downloader',
                'slug' => 'tiktok-mp4-downloader',
                'eyebrow' => 'TikTok Video Format',
                'heading' => 'Download Public TikTok Videos as [MP4]',
                'intro' => 'Paste a complete public TikTok video URL and select an MP4 option when that format is available from the source.',
                'meta_title' => 'TikTok MP4 Video Downloader | Save-Froms',
                'meta_description' => 'Download public TikTok videos as MP4 when available. Paste a complete video link and review quality and file size before saving.',
                'sort_order' => 7,
                'content' => '<h2>MP4 options for public TikTok videos</h2><p>MP4 is compatible with most modern phones, computers and media players. Save-Froms checks the submitted TikTok link and shows the formats returned by the provider. It does not promise that every TikTok post has an MP4 file or a particular resolution.</p><div class="tool-steps"><div><strong>Open TikTok</strong><span>Select the individual public video.</span></div><div><strong>Copy the link</strong><span>Use the post Share menu.</span></div><div><strong>Paste it above</strong><span>Submit the full or valid short URL.</span></div><div><strong>Choose MP4</strong><span>Review quality and estimated size.</span></div></div><h2>Full URLs and TikTok share links</h2><p>Both a complete TikTok video URL and a valid mobile share link can identify the post. Short links rely on a redirect and can expire or include tracking data. When a short link fails, open it in a browser and copy the final address after the redirect completes.</p><h2>Understanding the downloaded quality</h2><p>The provider can only return versions available for the original post. TikTok may compress uploads, so a large file is not always visually sharper. Review the listed quality and choose the file that suits your screen, connection and storage.</p><div class="note"><strong>No guaranteed watermark change:</strong> Save-Froms displays the resource returned by the provider. It does not promise a specific watermark result for every TikTok link.</div><h2>When a TikTok link will not work</h2><p>Private accounts, deleted posts, friends-only videos, active live streams, region limits and age restrictions can prevent public processing. Make sure the exact video opens without signing in. If it is public but temporarily fails, wait briefly and try again because provider availability can change.</p>',
                'faq_items' => [
                    ['question' => 'Can Save-Froms download private TikTok videos?', 'answer' => 'No. Private, friends-only and login-protected videos are outside the public-link workflow.'],
                    ['question' => 'Will every MP4 be watermark-free?', 'answer' => 'No fixed watermark outcome is guaranteed; it depends on the resource returned for that video.'],
                    ['question' => 'Why does my TikTok short link fail?', 'answer' => 'The redirect may be expired or blocked. Open it in a browser and copy the final video URL before trying again.'],
                ],
            ],
            [
                'title' => 'Download Troubleshooting Guide',
                'slug' => 'download-troubleshooting',
                'eyebrow' => 'Downloader Help',
                'heading' => 'Why Is My [Download Not Working]?',
                'intro' => 'Use this checklist to identify invalid links, privacy restrictions, expired requests, missing formats and temporary provider failures.',
                'meta_title' => 'Video Download Not Working? Troubleshooting Guide',
                'meta_description' => 'Fix common media downloader problems: invalid links, private videos, missing formats, expired downloads, browser issues and temporary provider errors.',
                'sort_order' => 8,
                'content' => '<h2>Start by checking the source link</h2><p>Open the URL in a private browser window. It should display one specific media item without requiring an account. Profile pages, feeds, playlists, search results and shortened links that no longer redirect are common causes of failure. Copy the final address from the browser after the media opens.</p><h2>Common error types and what they mean</h2><h3>The platform is not supported</h3><p>The domain is not active in Save-Froms or the submitted address belongs to a different website. Check the Supported Sites page and confirm the spelling of the hostname.</p><h3>The provider could not process the link</h3><p>The external provider returned no usable resources. This can be temporary, or the source may be private, deleted, restricted, live or unsupported. Wait briefly, confirm the link is public and try once more.</p><h3>A format stays on Preparing</h3><p>Some qualities require server-side processing. Large videos and separate audio/video streams can take longer. If preparation fails, choose another listed quality or analyse the link again to receive a fresh request.</p><h3>The download request expired</h3><p>For security and reliability, generated download links and preparation tokens are temporary. Return to the original downloader, paste the public link again and select the format promptly.</p><div class="tool-steps"><div><strong>Test the URL</strong><span>Open it without a signed-in session.</span></div><div><strong>Check restrictions</strong><span>Look for privacy, age or region gates.</span></div><div><strong>Try another format</strong><span>A single quality may be unavailable.</span></div><div><strong>Refresh the request</strong><span>Analyse again after a short delay.</span></div></div><h2>Browser and network checks</h2><ul><li>Disable an extension only if it is blocking the download button or redirect.</li><li>Allow downloads and pop-ups for the local or production domain when your browser asks.</li><li>Check available device storage.</li><li>Try a current version of Chrome, Safari, Edge or Firefox.</li><li>Avoid repeatedly submitting the same link because rate limits protect the service.</li></ul><div class="note"><strong>Do not attempt to bypass restrictions.</strong> Passwords, paid access, private accounts and regional controls are source-platform decisions and are not downloader errors.</div><h2>Why only some links fail</h2><p>Two links from the same platform can use different privacy settings, media delivery formats and geographic rules. The provider may support one post while another requires a login or uses an unsupported stream. This is why a successful test on one video does not guarantee every link from that platform.</p>',
                'faq_items' => [
                    ['question' => 'Should I remove tracking parameters from a link?', 'answer' => 'They are often harmless, but copying the clean final media URL can help when a shared redirect is broken.'],
                    ['question' => 'Why does one quality fail while another works?', 'answer' => 'Different qualities can use separate source streams or preparation methods, so availability can differ.'],
                    ['question' => 'Can Save-Froms bypass a private or region-blocked video?', 'answer' => 'No. Privacy, login, payment and regional restrictions cannot be bypassed.'],
                ],
            ],
            [
                'title' => 'How to Download Videos Online',
                'slug' => 'how-to-download-videos',
                'eyebrow' => 'How-to Guide',
                'heading' => 'How to Download Public Videos [Step by Step]',
                'intro' => 'Follow a simple browser workflow to check a supported public media link, compare available formats and save an authorized file.',
                'meta_title' => 'How to Download Public Videos Online | Save-Froms',
                'meta_description' => 'Learn how to copy a public media URL, check available video and audio formats, choose quality and download responsibly on mobile or desktop.',
                'sort_order' => 9,
                'content' => '<h2>Four steps from link to file</h2><p>Online media platforms use different share menus, but the Save-Froms workflow stays consistent. You need the complete URL of one publicly accessible video, Reel, clip or post from an active supported website.</p><div class="tool-steps"><div><strong>Copy the URL</strong><span>Open the individual media and use Share.</span></div><div><strong>Paste the link</strong><span>Place the complete address in the form.</span></div><div><strong>Compare options</strong><span>Review format, quality and file size.</span></div><div><strong>Download</strong><span>Save only media you may legally use.</span></div></div><h2>How to copy the correct link</h2><p>Choose the Share or Copy Link control attached to the individual media item. Do not copy a channel, profile, hashtag, feed or search URL. If an app gives you a short link, open it in a browser and confirm that it redirects to the expected public post.</p><h2>How to choose video quality</h2><p>Smaller resolutions generally download faster and use less storage. Higher resolutions can be clearer on large displays but are usually larger and sometimes require preparation. Select from the options actually shown for the source instead of expecting a fixed list.</p><h2>Video format or audio format?</h2><p>Choose MP4 or WEBM when you need the picture. Choose an audio-only option when you only need sound and the provider makes one available. Format labels do not guarantee identical compatibility on every device because the codec inside the file can differ.</p><h2>Downloading on a phone</h2><p>On Android, browser downloads commonly appear in the Downloads folder. On iPhone or iPad, Safari downloads normally appear in the Files app under Downloads. The exact location can change with browser and device settings.</p><h2>Downloading on Windows or macOS</h2><p>Desktop browsers usually save files to the configured Downloads folder or ask you where to save them. If the download opens in a new tab, use the browser media controls only when the source and your permissions allow saving.</p><div class="note"><strong>Use public links responsibly:</strong> being able to view a post does not mean you own it. Follow copyright law, creator permissions and the terms of the source platform.</div><h2>What Save-Froms does not do</h2><p>The website does not unlock private accounts, remove passwords, bypass subscriptions or guarantee a format that the source does not provide. It also does not permanently host the media file; temporary provider links can expire and must be generated again.</p>',
                'faq_items' => [
                    ['question' => 'Do I need a Save-Froms account?', 'answer' => 'No. The standard public-link workflow runs in the browser without a Save-Froms account.'],
                    ['question' => 'Where will the downloaded file be saved?', 'answer' => 'The location is controlled by your browser and device, usually a Downloads folder or the Files app.'],
                    ['question' => 'Can I download from an unsupported website?', 'answer' => 'No. The URL must belong to an active platform listed on the Supported Sites page.'],
                ],
            ],
        ];
    }

    public function down()
    {
        Schema::dropIfExists('landing_pages');
    }
}

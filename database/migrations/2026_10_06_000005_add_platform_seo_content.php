<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddPlatformSeoContent extends Migration
{
    public function up()
    {
        $pages = [
            'instagram-downloader' => [
                'name' => 'Instagram',
                'meta_title' => 'Instagram Video & Reels Downloader | Save-Froms',
                'meta_description' => 'Download publicly accessible Instagram videos and Reels online. Compare the available video quality and file size without installing an app.',
                'media' => 'public Instagram video posts and Reels',
                'input' => 'A complete public Instagram post or Reel URL',
                'output' => 'Source-dependent video formats and qualities',
                'best_for' => 'saving a public Reel or video post for permitted offline viewing',
                'restriction' => 'Private accounts, login-only posts, deleted media, Stories, expired links, and region-restricted content may not be available.',
                'specific' => '<h2>Instagram Reels and video-post links</h2><p>Instagram uses different URL paths for Reels and feed posts. Save-Froms accepts a complete public link copied from the Instagram Share menu. Avoid copying only a profile address, username, or search page because those links do not identify one media item.</p><h2>Choosing a file for mobile storage</h2><p>Reels are commonly watched on phones, so file size can matter as much as visual quality. Compare the returned options and choose a smaller file when mobile storage or data is limited. If only one option appears, that is the version currently available from the source.</p>',
                'faq_q' => 'Can Save-Froms download content from a private Instagram account?',
                'faq_a' => 'No. The downloader checks publicly accessible links and cannot bypass Instagram privacy controls, account logins, or restricted posts.',
            ],
            'tiktok-downloader' => [
                'name' => 'TikTok',
                'meta_title' => 'TikTok Video Downloader Online | Save-Froms',
                'meta_description' => 'Download public TikTok videos from a complete video link. View the available format, quality, and estimated file size in your browser.',
                'media' => 'public TikTok video links',
                'input' => 'A full TikTok video URL or valid TikTok short link',
                'output' => 'Video options returned for the public source',
                'best_for' => 'saving a public short-form video you are allowed to use',
                'restriction' => 'Private profiles, removed posts, photo-only posts, live streams, age restrictions, and regional limits can prevent processing.',
                'specific' => '<h2>Full TikTok links and short links</h2><p>You can copy a TikTok link from the Share menu in the app or browser. Short links normally redirect to the complete video page, but a broken, expired, or tracking-only link may fail. If that happens, open the video in a browser and copy the final address from the URL bar.</p><h2>What appears in the TikTok results?</h2><p>The results show only the formats supplied for the submitted source. Save-Froms does not promise a particular resolution or watermark state. Check the displayed format, quality, and estimated file size before starting the download.</p>',
                'faq_q' => 'Does the TikTok download always remove a watermark?',
                'faq_a' => 'No fixed watermark result is promised. The downloaded file depends on the version returned by the media provider for that public link.',
            ],
            'facebook-downloader' => [
                'name' => 'Facebook',
                'meta_title' => 'Facebook Video Downloader Online | Save-Froms',
                'meta_description' => 'Download publicly accessible Facebook videos online. Paste one complete public video link and compare the available qualities before saving.',
                'media' => 'public Facebook video and Watch links',
                'input' => 'One complete public Facebook video URL',
                'output' => 'Available source-dependent video qualities',
                'best_for' => 'saving public videos you own or have permission to download',
                'restriction' => 'Friends-only posts, private groups, login-required pages, deleted videos, live broadcasts, and geographic restrictions may not work.',
                'specific' => '<h2>Public Facebook video links</h2><p>Facebook links can point to profiles, pages, posts, Watch videos, groups, or login screens. For the best result, open the individual public video and copy its direct Share link. A general page or profile URL does not identify the exact video to process.</p><h2>Why Facebook availability can change</h2><p>A video owner can change the audience, remove the post, or restrict playback at any time. A link that worked earlier may stop returning formats after its privacy setting changes. Save-Froms respects those access controls and cannot unlock restricted media.</p>',
                'faq_q' => 'Can I download a video from a private Facebook group?',
                'faq_a' => 'No. Group-only, friends-only, and login-protected videos are not public sources and cannot be accessed through this workflow.',
            ],
            'twitter-downloader' => [
                'name' => 'X (Twitter)',
                'meta_title' => 'X (Twitter) Video Downloader | Save-Froms',
                'meta_description' => 'Download public videos from X or Twitter links. Check available quality and file size online without creating a Save-Froms account.',
                'media' => 'public video posts on X and legacy Twitter URLs',
                'input' => 'A complete x.com or twitter.com post URL',
                'output' => 'Available video files returned for that post',
                'best_for' => 'saving a public post video for authorized offline use',
                'restriction' => 'Protected accounts, deleted posts, sensitive-content gates, live broadcasts, and posts without downloadable video may return no result.',
                'specific' => '<h2>X.com and Twitter.com links</h2><p>Save-Froms recognizes both current x.com post addresses and older twitter.com links. Copy the URL of the individual post containing the video—not a profile, hashtag, search result, or timeline page.</p><h2>Posts with multiple media items</h2><p>Some posts contain more than one photo, clip, or animated media item. The provider determines which downloadable resources are returned. Review the title, quality, format, and size shown in the result before choosing a file.</p>',
                'faq_q' => 'Can I download from a protected X account?',
                'faq_a' => 'No. Protected-account posts are not public, and Save-Froms cannot bypass account or audience restrictions.',
            ],
            'vimeo-downloader' => [
                'name' => 'Vimeo',
                'meta_title' => 'Vimeo Video Downloader Online | Save-Froms',
                'meta_description' => 'Download publicly accessible Vimeo videos in the qualities returned for the source. Compare format and file size in your browser.',
                'media' => 'publicly accessible Vimeo video pages',
                'input' => 'One complete public Vimeo video URL',
                'output' => 'Source-dependent video formats and resolutions',
                'best_for' => 'saving an authorized public Vimeo video for offline playback',
                'restriction' => 'Password-protected, private, domain-restricted, rental, live, or owner-disabled videos may not provide downloadable formats.',
                'specific' => '<h2>Vimeo privacy and creator controls</h2><p>Vimeo gives creators detailed privacy, embedding, and download controls. Save-Froms can only process resources that are publicly available to the submitted link. It cannot remove a password, bypass a domain restriction, or override the creator’s access settings.</p><h2>Resolution and file-size choices</h2><p>Professional Vimeo uploads may provide several resolutions. A higher resolution can look sharper on a large screen but usually requires more storage and bandwidth. Choose a smaller quality for phones or limited data, and a larger quality when detail is more important.</p>',
                'faq_q' => 'Can Save-Froms unlock a password-protected Vimeo video?',
                'faq_a' => 'No. Passwords, private sharing settings, and creator restrictions must be respected and cannot be bypassed.',
            ],
            'dailymotion-downloader' => [
                'name' => 'Dailymotion',
                'meta_title' => 'Dailymotion Video Downloader | Save-Froms',
                'meta_description' => 'Download public Dailymotion videos from a complete link. View available format, resolution, and estimated file size before saving.',
                'media' => 'public Dailymotion video pages and valid dai.ly links',
                'input' => 'A complete dailymotion.com or dai.ly video URL',
                'output' => 'Available video qualities supplied for the source',
                'best_for' => 'saving permitted news, entertainment, or creator videos for offline viewing',
                'restriction' => 'Private, deleted, live, age-gated, geo-blocked, or rights-restricted videos may be unavailable.',
                'specific' => '<h2>Dailymotion and dai.ly URLs</h2><p>Dailymotion may provide a standard website address or a shortened dai.ly share link. Both should identify one public video. If a short link does not process, open it in your browser and copy the final Dailymotion URL after the redirect.</p><h2>Source quality determines the result</h2><p>The downloader cannot create detail that is missing from the original upload. If the public source offers only a limited resolution, higher-quality buttons will not appear. Use the listed resolution and size to choose the most practical available file.</p>',
                'faq_q' => 'Why is HD missing for some Dailymotion videos?',
                'faq_a' => 'HD appears only when the source and provider return that quality. Older or lower-resolution uploads may offer SD options only.',
            ],
            'twitch-downloader' => [
                'name' => 'Twitch',
                'meta_title' => 'Twitch Clip Downloader Online | Save-Froms',
                'meta_description' => 'Download publicly accessible Twitch clips and supported video links. Paste a complete URL and review the available quality online.',
                'media' => 'public Twitch clips and supported recorded-video links',
                'input' => 'A complete twitch.tv or clips.twitch.tv URL',
                'output' => 'Available video resources returned for the clip or recording',
                'best_for' => 'saving a public clip or permitted recording before it expires',
                'restriction' => 'Live streams, subscriber-only videos, deleted clips, expired VODs, mature-content gates, and restricted channels may not work.',
                'specific' => '<h2>Twitch clips, VODs, and live streams</h2><p>Short public clips are usually the clearest source type because they identify one saved highlight. Recorded broadcasts can expire or become restricted, while an active live stream is not a completed downloadable file. Wait for a broadcast to become an accessible recording before trying its URL.</p><h2>Save clips before availability changes</h2><p>Twitch clips and recordings can be removed by the creator or platform and some VODs expire automatically. If you have permission to save a public clip, check the available quality and download it while the source remains accessible.</p>',
                'faq_q' => 'Can I download a Twitch live stream while it is broadcasting?',
                'faq_a' => 'The standard workflow is intended for completed public clips or supported recordings, not active live-stream capture.',
            ],
            'pinterest-downloader' => [
                'name' => 'Pinterest',
                'meta_title' => 'Pinterest Video Downloader Online | Save-Froms',
                'meta_description' => 'Download publicly accessible Pinterest video Pins from a complete Pin link. Review the available media format before saving.',
                'media' => 'public Pinterest Pins that contain supported video media',
                'input' => 'A complete pinterest.com Pin URL or valid pin.it link',
                'output' => 'Available media resources returned for that Pin',
                'best_for' => 'saving a public video Pin you own or are authorized to use',
                'restriction' => 'Private boards, login-only Pins, image-only Pins, removed content, shopping redirects, and unsupported media may return no video.',
                'specific' => '<h2>Video Pins and image Pins</h2><p>Pinterest hosts several content types. A standard image Pin does not contain a downloadable video, while Idea Pins and video Pins may include supported media. Copy the link to the individual Pin instead of a board, profile, search page, or outbound shopping website.</p><h2>Using pin.it short links</h2><p>The Pinterest app often shares a pin.it address. If the short link cannot be processed, open it in a browser, allow it to redirect to the full Pin page, and copy the final URL before trying again.</p>',
                'faq_q' => 'Why does my Pinterest link show no video?',
                'faq_a' => 'The Pin may contain only an image, link to an external shop, be private, or use a media type that is not available to the provider.',
            ],
        ];

        foreach ($pages as $slug => $page) {
            DB::table('supported_sites')->where('slug', $slug)->update([
                'description' => $this->buildContent($page),
                'meta_title' => $page['meta_title'],
                'meta_description' => $page['meta_description'],
                'updated_at' => now(),
            ]);
        }
    }

    private function buildContent(array $page)
    {
        $name = $page['name'];
        return '<h2>What can Save-Froms download from a '.$name.' link?</h2>'
            .'<p>Save-Froms checks one complete public link and displays the downloadable resources currently returned for that source. It is designed for '.$page['media'].'. The result identifies the available format, quality, and estimated file size before you choose a download.</p>'
            .'<p>This browser-based workflow is useful for '.$page['best_for'].'. No Save-Froms account or separate application is required.</p>'
            .'<div class="sf-facts"><div><strong>Input</strong><span>'.$page['input'].'</span></div><div><strong>Output</strong><span>'.$page['output'].'</span></div><div><strong>Devices</strong><span>Android, iPhone, tablets, Windows, and macOS</span></div><div><strong>Installation</strong><span>No separate app required</span></div></div>'
            .'<div class="sf-notice"><strong>Availability notice:</strong> '.$page['restriction'].' Only download content you own or have permission to use.</div>'
            .'<h2>How to download from '.$name.'</h2><ol class="sf-steps"><li><strong>Open the public media</strong><span>Find the individual '.$name.' post, video, or clip you want to save.</span></li><li><strong>Copy its complete link</strong><span>Use the platform Share option or copy the final URL from your browser.</span></li><li><strong>Paste the URL above</strong><span>Submit the link and wait for the source-dependent download choices.</span></li><li><strong>Choose and download</strong><span>Compare format, quality, and size, then press Download beside your preferred option.</span></li></ol>'
            .$page['specific']
            .'<h2>Works on mobile and desktop browsers</h2><p>Use Save-Froms in a modern browser on Android, iPhone, iPad, Windows, or macOS. The responsive interface keeps the same workflow on every device: copy a public link, paste it into the form, review the available choices, and save the permitted file.</p>'
            .'<h2>Frequently asked questions</h2><div class="sf-faq"><details><summary>How do I download from '.$name.'?</summary><p>Copy one complete public '.$name.' media link, paste it into the downloader above, and choose a format returned for that source.</p></details><details><summary>Is an account or app required?</summary><p>No Save-Froms account or separate app is required. The standard workflow runs in your browser.</p></details><details><summary>Why are some qualities missing?</summary><p>Quality options depend on the original upload and the resources returned by the media provider. Save-Froms displays only the choices currently available.</p></details><details><summary>'.$page['faq_q'].'</summary><p>'.$page['faq_a'].'</p></details><details><summary>Can I download any public media?</summary><p>Technical availability does not grant usage rights. Download only media you own or have explicit permission to save.</p></details></div>'
            .'<p><small>Page information updated: October 6, 2026.</small></p>';
    }

    public function down()
    {
        // Content remains editable from the administrator panel after rollback.
    }
}

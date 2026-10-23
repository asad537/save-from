<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ExpandInstagramReelsAndYoutubeMp4ConverterPages extends Migration
{
    public function up()
    {
        $now = now();

        foreach ($this->pages() as $slug => $page) {
            $record = DB::table('landing_pages')->where('slug', $slug)->first();
            if (!$record) {
                continue;
            }

            $content = $record->content;
            if (strpos($content, $page['marker']) === false) {
                $content .= "\n".$page['section'];
            }

            $faq = json_decode($record->faq_items, true) ?: [];
            foreach ($page['faq_items'] as $item) {
                if (!collect($faq)->contains('question', $item['question'])) {
                    $faq[] = $item;
                }
            }

            DB::table('landing_pages')->where('id', $record->id)->update([
                'title' => $page['title'],
                'heading' => $page['heading'],
                'intro' => $page['intro'],
                'meta_title' => $page['meta_title'],
                'meta_description' => $page['meta_description'],
                'content' => $content,
                'faq_items' => json_encode($faq, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'updated_at' => $now,
            ]);
        }
    }

    private function pages()
    {
        return [
            'instagram-reels-downloader' => [
                'title' => 'Instagram Reels Downloader',
                'heading' => 'Public [Instagram Reels Downloader]',
                'intro' => 'Paste one complete public Instagram Reel link to check its available video format and file size before saving media you are authorised to keep.',
                'meta_title' => 'Instagram Reels Downloader – Public Reel Video Tool | Save-Froms',
                'meta_description' => 'Use an Instagram Reels downloader for public Reel links. Copy one Reel URL, review the available video format and size, then save authorised media in your browser.',
                'marker' => 'How an Instagram Reels downloader handles public links',
                'section' => <<<'HTML'
<h2>How an Instagram Reels downloader handles public links</h2>
<p>An Instagram Reels downloader needs the link to one Reel, not an account profile, Explore page, hashtag or music page. Open the Reel itself, choose <strong>Share</strong>, and use <strong>Copy link</strong>. If the copied URL opens the same Reel in a private browser window, it is the right kind of public source to check above.</p>
<p>Result choices depend on the Reel's source delivery. Some public Reels return one useful video file; others may return different sizes or quality choices. Check the listed format and estimated file size instead of assuming that every Reel has an HD option. A downloader cannot restore detail removed by the original upload or Instagram's processing.</p>
<h2>Reel links that are not the same as video links</h2>
<table><thead><tr><th>What you copied</th><th>Will it identify one Reel?</th><th>What to do</th></tr></thead><tbody><tr><td>An individual <code>/reel/</code> URL</td><td>Usually, if it is public</td><td>Paste it directly into the form.</td></tr><tr><td>A profile or creator page</td><td>No, it contains many posts</td><td>Open the specific Reel and copy its Share link.</td></tr><tr><td>A photo-only post</td><td>No, it is not a video source</td><td>Use an individual video post or Reel instead.</td></tr><tr><td>A private or login-only Reel</td><td>No, it is restricted media</td><td>Use Instagram's approved sharing options.</td></tr></tbody></table>
<h2>Use Reels responsibly</h2>
<p>A public Reel can still contain copyrighted music, a creator's performance or personal footage. Public playback is not permission to repost, edit or distribute it. Save your own videos, approved client assets or other material for which you have clear permission. If a temporary download link later expires, return to the original Reel URL for a fresh result.</p>
HTML,
                'faq_items' => [
                    ['question' => 'Can an Instagram Reels downloader access a private Reel?', 'answer' => 'No. Private, login-protected and restricted Reels are not public sources.'],
                    ['question' => 'Why is a Reel quality missing?', 'answer' => 'The source video and the resources returned for that individual Reel determine which qualities can appear.'],
                ],
            ],
            'youtube-to-mp4' => [
                'title' => 'YouTube to MP4 Converter',
                'heading' => 'Use a [YouTube to MP4 Converter]',
                'intro' => 'Paste one complete public YouTube link to check available MP4 video formats, compare resolution and file size, and save authorised media.',
                'meta_title' => 'YouTube to MP4 Converter – Public Video Formats | Save-Froms',
                'meta_description' => 'Use a YouTube to MP4 converter for public video links. Compare available MP4 resolution and file size before saving authorised media in your browser.',
                'marker' => 'What does a YouTube to MP4 converter do?',
                'section' => <<<'HTML'
<h2>What does a YouTube to MP4 converter do?</h2>
<p>A YouTube to MP4 converter checks one public YouTube video URL and lists an MP4 option when the source returns one. It does not create a better-quality version of the video. The original upload, YouTube's available streams and the compatible resources returned for that link decide what format and resolution can appear.</p>
<p>Use a normal watch URL, a <code>youtu.be</code> share link or an individual Shorts link. A channel, playlist, search page or general Shorts feed contains more than one item, so it is not a reliable source for a single file result. Private, members-only, deleted, age-restricted and region-restricted videos remain unavailable through a public-link workflow.</p>
<h2>How to choose an MP4 resolution</h2>
<table><thead><tr><th>Situation</th><th>Practical choice</th><th>Why</th></tr></thead><tbody><tr><td>Phone viewing</td><td>A compact listed MP4</td><td>It can save storage and download more quickly.</td></tr><tr><td>Laptop or larger screen</td><td>A higher listed resolution</td><td>Extra detail may be visible when the source is genuinely HD.</td></tr><tr><td>Limited storage</td><td>Compare estimated file sizes</td><td>Duration and bitrate affect size as well as resolution.</td></tr><tr><td>Authorised editing project</td><td>The clearest compatible listed MP4</td><td>Keep the original source link and creator approval too.</td></tr></tbody></table>
<p>Higher resolutions can be delivered as separate video and audio streams. That can mean a short preparation step before a file is ready. If a temporary link later returns 403, paste the original public YouTube URL again and choose from the new result list.</p>
<h2>Permission and quality limits</h2>
<p>Public availability does not transfer copyright. Only save YouTube media you created, have licensed or have permission to keep. The <a href="/blog/video-resolution-vs-file-size-360p-720p-1080p">resolution and file-size guide</a> explains why the highest number is not always the best choice for every device.</p>
HTML,
                'faq_items' => [
                    ['question' => 'Does a YouTube to MP4 converter provide 1080p for every link?', 'answer' => 'No. The original video and source resources determine which MP4 qualities are available for each public link.'],
                    ['question' => 'Can a converter make an SD YouTube video into HD?', 'answer' => 'No. Conversion cannot recreate detail that was not present in the original source.'],
                ],
            ],
        ];
    }

    public function down()
    {
        // SEO content updates are intentionally retained.
    }
}

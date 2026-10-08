<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Duplicate-content pass: the same legal sentences, "updated" line, generic
 * "Can I download any public ...?" FAQ and generic "expired" troubleshooting row
 * appeared on every platform page. The layout already shows a responsible-use
 * notice and the site-wide FAQ/troubleshooting hub cover the generic answers,
 * so each page keeps only its platform-specific text.
 */
class RemoveRepeatedPlatformBoilerplate extends Migration
{
    public function up()
    {
        $closing = [
            'instagram-downloader' => 'Save-Froms respects Instagram privacy settings and the rights of the people who post there.',
            'tiktok-downloader' => 'Sounds and clips belong to the creators who posted them; save only what you are allowed to keep.',
            'facebook-downloader' => 'Audience settings are the owner\'s decision and Save-Froms does not work around them.',
            'twitter-downloader' => 'Protected accounts and deleted posts stay out of reach, and the author keeps all rights to the clip.',
            'vimeo-downloader' => 'Vimeo creators control who can watch and download their work; those settings are honoured.',
            'dailymotion-downloader' => 'Rights holders decide where a Dailymotion video may play, and those limits are respected.',
            'twitch-downloader' => 'Clips and VODs remain the streamer\'s property; save them only with permission.',
        ];

        foreach (DB::table('supported_sites')->whereIn('slug', array_keys($closing))->get() as $site) {
            $html = $site->description;

            // Generic closing sentence of the availability notice -> platform-specific sentence.
            $html = str_replace(' Only download content you own or have permission to use.</div>', ' '.$closing[$site->slug].'</div>', $html);

            // Generic final FAQ entry (same answer on every page).
            $html = preg_replace('#<details><summary>Can I download any public (?:video|Reel|TikTok|clip)\?</summary><p>Technical availability does not grant usage rights\. Download only media you own or have explicit permission to save\.</p></details>#', '', $html);

            // Generic "expired" troubleshooting row; covered by the FAQ and troubleshooting hub.
            $html = preg_replace('#<tr><td>Download expired</td><td>More than 20 minutes passed</td><td>Analyse again for a fresh result</td></tr>\s*#', '', $html);

            // Dated footer line; the view now prints the record\'s updated date instead.
            $html = preg_replace('#\s*<p><small>Page information updated: October 8, 2026\.</small></p>\s*$#', '', $html);

            DB::table('supported_sites')->where('id', $site->id)->update(['description' => $html, 'updated_at' => now()]);
        }

        $youtube = DB::table('supported_sites')->where('slug', 'youtube-downloader')->first();
        if ($youtube) {
            $html = $youtube->description;
            $html = str_replace(' Download only content you own or have permission to use.</div>', ' YouTube\'s own terms continue to apply to every video, whichever tool you use.</div>', $html);
            $html = preg_replace('#<tr><td>Download expired</td><td>More than 20 minutes passed</td><td>Paste the link again</td></tr>\s*#', '', $html);
            $html = preg_replace('#\s*<p><small>Page and workflow information updated: October 8, 2026\.</small></p>\s*$#', '', $html);
            DB::table('supported_sites')->where('id', $youtube->id)->update(['description' => $html, 'updated_at' => now()]);
        }
    }

    public function down()
    {
        // Content-only change; nothing to revert structurally.
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Internal-linking pass (2026-10-27). Each English post, tool page and
 * platform page gets up to four contextual links inside its body text to
 * the matching tool or guide (platform-aware anchors such as "Shorts",
 * "Reel", "Stories", "720p", "403", "watermark"). The exact paragraph
 * replacements were generated offline and live in
 * database/data/internal-links-2026-10-27.json so the change is reviewable
 * and idempotent: a paragraph is only replaced while it still matches.
 */
class AddContextualInternalLinks extends Migration
{
    public function up()
    {
        $pages = json_decode(file_get_contents(database_path('data/internal-links-2026-10-27.json')), true) ?: [];
        $now = now();

        foreach ($pages as $page) {
            $row = DB::table($page['table'])->where('slug', $page['slug'])->first();
            if (!$row) {
                continue;
            }
            $content = $row->{$page['column']};
            $changed = false;
            foreach ($page['replacements'] as [$old, $new]) {
                if (substr_count($content, $old) === 1) {
                    $content = str_replace($old, $new, $content);
                    $changed = true;
                }
            }
            if ($changed) {
                DB::table($page['table'])->where('id', $row->id)->update([$page['column'] => $content, 'updated_at' => $now]);
            }
        }
    }

    public function down()
    {
        $pages = json_decode(file_get_contents(database_path('data/internal-links-2026-10-27.json')), true) ?: [];
        foreach ($pages as $page) {
            $row = DB::table($page['table'])->where('slug', $page['slug'])->first();
            if (!$row) {
                continue;
            }
            $content = $row->{$page['column']};
            foreach ($page['replacements'] as [$old, $new]) {
                $content = str_replace($new, $old, $content);
            }
            DB::table($page['table'])->where('id', $row->id)->update([$page['column'] => $content]);
        }
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Every post that used a template SVG cover (some shared by two or three
 * posts) gets its own designed cover carrying the guide's title.
 * Mapping lives in database/data/covers-2026-10-29.json.
 */
class AssignUniqueDesignedCovers extends Migration
{
    public function up()
    {
        $map = json_decode(file_get_contents(database_path('data/covers-2026-10-29.json')), true) ?: [];
        foreach ($map as $slug => $image) {
            if (file_exists(public_path($image))) {
                DB::table('blog_posts')->where('slug', $slug)->update(['featured_image' => $image, 'updated_at' => now()]);
            }
        }
    }

    public function down()
    {
        // Previous covers are retained on disk; forward-only.
    }
}

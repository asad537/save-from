<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * 2026-10-27: the provider parses Pinterest video Pins (720p row appears) but
 * its prepare step returns "failed" for every Pin tested, three attempts each,
 * so users would get an error after pressing Download. The page content is
 * kept; flip is_active to 1 once a Pin prepares successfully.
 */
class DeactivatePinterestUntilProviderPrepareWorks extends Migration
{
    public function up()
    {
        DB::table('supported_sites')->where('slug', 'pinterest-downloader')->update(['is_active' => 0, 'updated_at' => now()]);
    }

    public function down()
    {
        DB::table('supported_sites')->where('slug', 'pinterest-downloader')->update(['is_active' => 1, 'updated_at' => now()]);
    }
}

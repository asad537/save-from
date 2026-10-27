<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class TrimHubMetaDescriptions extends Migration
{
    public function up()
    {
        $descriptions = [
            'download-troubleshooting' => 'Fix a video download that is not working: what each error means, private and expired links, Preparing that never finishes, 403 files, browser and phone issues.',
            'how-to-download-videos' => 'How to download videos online from a public link on any platform: copy the right link, check the formats, pick a quality, and find the saved file.',
        ];
        foreach ($descriptions as $slug => $description) {
            DB::table('landing_pages')->where('slug', $slug)->update(['meta_description' => $description, 'updated_at' => now()]);
        }
    }

    public function down()
    {
        // Forward-only.
    }
}

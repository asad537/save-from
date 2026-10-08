<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVisitorKeyToAnalyticsEventsTable extends Migration
{
    public function up()
    {
        Schema::table('analytics_events', function (Blueprint $table) {
            $table->string('visitor_key', 64)->nullable()->after('event_type')->index();
        });
    }

    public function down()
    {
        Schema::table('analytics_events', function (Blueprint $table) {
            $table->dropIndex(['visitor_key']);
            $table->dropColumn('visitor_key');
        });
    }
}

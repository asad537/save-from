<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalyticsEventsTable extends Migration
{
    public function up()
    {
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('event_type', 20)->index();
            $table->string('ip_address', 45)->nullable()->index();
            $table->text('user_agent')->nullable();
            $table->string('url', 2048)->nullable();
            $table->string('referrer', 2048)->nullable();
            $table->string('platform', 120)->nullable()->index();
            $table->string('media_title', 500)->nullable();
            $table->string('format', 30)->nullable();
            $table->string('quality', 60)->nullable();
            $table->timestamp('created_at')->useCurrent()->index();
            $table->index(['event_type', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('analytics_events');
    }
}

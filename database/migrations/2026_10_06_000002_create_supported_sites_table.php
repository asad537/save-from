<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSupportedSitesTable extends Migration
{
    public function up()
    {
        Schema::create('supported_sites', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('domains');
            $table->string('logo_url', 2048)->nullable();
            $table->text('description')->nullable();
            $table->string('meta_title', 70)->nullable();
            $table->string('meta_description', 180)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });

        $now = now();
        DB::table('supported_sites')->insert([
            ['name'=>'YouTube','slug'=>'youtube-downloader','domains'=>'youtube.com,youtu.be','logo_url'=>'https://cdn.simpleicons.org/youtube/FF0000','description'=>'Download public YouTube videos in available formats.','meta_title'=>'YouTube Video Downloader','meta_description'=>'Download public YouTube videos in available video and audio formats.','is_active'=>1,'sort_order'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Instagram','slug'=>'instagram-downloader','domains'=>'instagram.com','logo_url'=>'https://cdn.simpleicons.org/instagram/E4405F','description'=>'Download public Instagram media.','meta_title'=>'Instagram Media Downloader','meta_description'=>'Save public Instagram media quickly with Save-Froms.','is_active'=>1,'sort_order'=>2,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'TikTok','slug'=>'tiktok-downloader','domains'=>'tiktok.com','logo_url'=>'https://cdn.simpleicons.org/tiktok/000000','description'=>'Download public TikTok videos.','meta_title'=>'TikTok Video Downloader','meta_description'=>'Download public TikTok videos in available quality.','is_active'=>1,'sort_order'=>3,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Facebook','slug'=>'facebook-downloader','domains'=>'facebook.com,fb.watch','logo_url'=>'https://cdn.simpleicons.org/facebook/0866FF','description'=>'Download public Facebook videos.','meta_title'=>'Facebook Video Downloader','meta_description'=>'Save public Facebook videos with a simple link.','is_active'=>1,'sort_order'=>4,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Twitter / X','slug'=>'twitter-downloader','domains'=>'twitter.com,x.com','logo_url'=>'https://cdn.simpleicons.org/x/000000','description'=>'Download public videos from X.','meta_title'=>'X (Twitter) Video Downloader','meta_description'=>'Download public videos from X and Twitter.','is_active'=>1,'sort_order'=>5,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Vimeo','slug'=>'vimeo-downloader','domains'=>'vimeo.com','logo_url'=>'https://cdn.simpleicons.org/vimeo/1AB7EA','description'=>'Download public Vimeo media.','meta_title'=>'Vimeo Video Downloader','meta_description'=>'Download publicly accessible Vimeo videos.','is_active'=>1,'sort_order'=>6,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Dailymotion','slug'=>'dailymotion-downloader','domains'=>'dailymotion.com,dai.ly','logo_url'=>'https://cdn.simpleicons.org/dailymotion/0D0D0D','description'=>'Download public Dailymotion media.','meta_title'=>'Dailymotion Video Downloader','meta_description'=>'Download public Dailymotion videos in available formats.','is_active'=>1,'sort_order'=>7,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Twitch','slug'=>'twitch-downloader','domains'=>'twitch.tv,clips.twitch.tv','logo_url'=>'https://cdn.simpleicons.org/twitch/9146FF','description'=>'Download publicly accessible Twitch clips.','meta_title'=>'Twitch Clip Downloader','meta_description'=>'Download public Twitch clips with Save-Froms.','is_active'=>1,'sort_order'=>8,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Pinterest','slug'=>'pinterest-downloader','domains'=>'pinterest.com,pin.it','logo_url'=>'https://cdn.simpleicons.org/pinterest/BD081C','description'=>'Download public Pinterest media.','meta_title'=>'Pinterest Media Downloader','meta_description'=>'Save public Pinterest media from a link.','is_active'=>0,'sort_order'=>9,'created_at'=>$now,'updated_at'=>$now],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('supported_sites');
    }
}

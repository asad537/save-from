<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateFaqsTable extends Migration
{
    public function up()
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->longText('answer');
            $table->string('category', 100)->nullable()->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        $now = now();
        DB::table('faqs')->insert([
            ['question' => 'How do I download a video with Save-Froms?', 'answer' => '<p>Copy the complete public media URL from the supported website, paste it into the downloader, and press <strong>Download</strong>. When the available formats appear, choose the quality and file type you want.</p>', 'category' => 'Getting Started', 'sort_order' => 1, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['question' => 'Is Save-Froms free to use?', 'answer' => '<p>Yes. The browser-based downloader is free to use and does not require a Save-Froms account. Your network provider may still charge for mobile data or internet usage.</p>', 'category' => 'Getting Started', 'sort_order' => 2, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['question' => 'Which websites are supported?', 'answer' => '<p>Save-Froms supports the active platforms shown on the Supported Sites section, including YouTube, Instagram, TikTok, Facebook, X, Vimeo, Dailymotion and Twitch. Availability can vary for individual links and media types.</p>', 'category' => 'Supported Sites', 'sort_order' => 3, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['question' => 'Why does a public link sometimes fail?', 'answer' => '<p>A link can fail when the source is deleted, private, age-restricted, region-restricted, live, login-only or temporarily unavailable. The external media provider can also experience a temporary processing delay. Confirm that the complete public URL opens normally, then try again after a short time.</p>', 'category' => 'Troubleshooting', 'sort_order' => 4, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['question' => 'Why are some video qualities unavailable?', 'answer' => '<p>Save-Froms displays only the formats returned for that source. Available resolution depends on the original upload and the media provider. A video uploaded in lower quality cannot provide a genuine higher-resolution download.</p>', 'category' => 'Formats & Quality', 'sort_order' => 5, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['question' => 'What is the difference between MP4, WEBM and MP3?', 'answer' => '<p><strong>MP4</strong> and <strong>WEBM</strong> are video formats that can include picture and sound. <strong>MP3</strong> is audio-only. Choose a video format for offline viewing or MP3 when you only need the available audio.</p>', 'category' => 'Formats & Quality', 'sort_order' => 6, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['question' => 'Does Save-Froms work on phones and tablets?', 'answer' => '<p>Yes. The website works in modern mobile browsers on Android phones, iPhone and tablets. The final save location and download controls depend on your browser and device settings.</p>', 'category' => 'Devices', 'sort_order' => 7, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['question' => 'Do I need to install an application or extension?', 'answer' => '<p>No. The standard Save-Froms workflow runs directly in your browser. Paste a supported public URL and select one of the formats returned for that media.</p>', 'category' => 'Devices', 'sort_order' => 8, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['question' => 'Can Save-Froms download private or login-protected media?', 'answer' => '<p>No. Save-Froms cannot bypass passwords, private accounts, paid access, account logins or platform privacy controls. Only publicly accessible media can be processed.</p>', 'category' => 'Privacy & Access', 'sort_order' => 9, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['question' => 'Am I allowed to download any media I find online?', 'answer' => '<p>No. Technical availability does not grant copyright or usage rights. Download only content you own, content in the public domain, or content you have permission to save and use. Follow the source platform rules and applicable law.</p>', 'category' => 'Legal', 'sort_order' => 10, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('faqs');
    }
}

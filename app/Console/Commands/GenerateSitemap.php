<?php

namespace App\Console\Commands;

use App\Services\SitemapGenerator;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the public static XML sitemap';

    public function handle(SitemapGenerator $generator): int
    {
        $this->info('Sitemap generated: '.$generator->write());

        return 0;
    }
}

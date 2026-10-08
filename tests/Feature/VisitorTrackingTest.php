<?php

namespace Tests\Feature;

use App\Models\AnalyticsEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitorTrackingTest extends TestCase
{
    use RefreshDatabase;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');
    }

    public function test_multiple_pages_create_one_daily_visitor_and_multiple_page_views()
    {
        $visitorKey = hash('sha256', 'same-browser');

        $this->withSession(['analytics_visitor_key' => $visitorKey])->get('/')->assertOk();
        $this->withSession(['analytics_visitor_key' => $visitorKey])->get('/blog')->assertOk();

        $this->assertSame(1, AnalyticsEvent::where('visitor_key', $visitorKey)->where('event_type', 'visit')->count());
        $this->assertSame(2, AnalyticsEvent::where('visitor_key', $visitorKey)->where('event_type', 'page_view')->count());
    }
}

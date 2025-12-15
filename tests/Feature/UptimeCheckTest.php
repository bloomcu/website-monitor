<?php

namespace Tests\Feature;

use App\Jobs\CheckPageUptime;
use App\Models\Page;
use App\Models\Website;
use App\Models\PageCheck;
use App\Services\PageUptimeChecker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UptimeCheckTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_checks_page_uptime_and_records_success()
    {
        Http::fake([
            'example.com/*' => Http::response('OK', 200),
        ]);

        $user = \App\Models\User::factory()->create();
        $website = Website::create(['user_id' => $user->id, 'name' => 'Example', 'base_url' => 'https://example.com']);
        $page = Page::create(['website_id' => $website->id, 'url' => 'https://example.com']);

        $job = new CheckPageUptime($page);
        $job->handle(new PageUptimeChecker());

        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'is_up' => true,
            'last_status_code' => 200,
        ]);

        $this->assertDatabaseHas('page_checks', [
            'page_id' => $page->id,
            'status' => 'up',
            'status_code' => 200,
        ]);
    }

    public function test_it_checks_page_uptime_and_records_failure()
    {
        Http::fake([
            '*' => Http::response('Internal Server Error', 500),
        ]);

        $user = \App\Models\User::factory()->create();
        $website = Website::create(['user_id' => $user->id, 'name' => 'Example', 'base_url' => 'https://example.org']);
        $page = Page::create(['website_id' => $website->id, 'url' => 'https://example.org']);

        $job = new CheckPageUptime($page);
        $job->handle(new PageUptimeChecker());

        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'is_up' => false,
            'last_status_code' => 500,
        ]);

        $this->assertDatabaseHas('page_checks', [
            'page_id' => $page->id,
            'status' => 'down',
            'status_code' => 500,
        ]);
    }
}

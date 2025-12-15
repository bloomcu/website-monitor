<?php

namespace App\Jobs;

use App\Models\Page;
use App\Models\PageCheck;
use App\Services\PageUptimeChecker;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckPageUptime implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Page $page)
    {
    }

    public function handle(PageUptimeChecker $checker): void
    {
        $result = $checker->check($this->page->url);

        PageCheck::create([
            'page_id' => $this->page->id,
            'status' => $result['status'],
            'status_code' => $result['status_code'],
            'response_time_ms' => $result['response_time_ms'],
            'checked_at' => now(),
        ]);

        $this->page->update([
            'is_up' => $result['status'] === 'up',
            'last_checked_at' => now(),
            'last_status_code' => $result['status_code'],
            'last_response_time_ms' => $result['response_time_ms'],
        ]);
    }
}

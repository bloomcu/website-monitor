<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Jobs\CheckPageUptime;
use Illuminate\Http\JsonResponse;

class PageUptimeController extends Controller
{
    public function check(Page $page): JsonResponse
    {
        CheckPageUptime::dispatch($page);

        return response()->json([
            'message' => 'Uptime check queued successfully',
        ]);
    }
}


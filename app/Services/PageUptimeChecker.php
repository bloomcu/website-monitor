<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

class PageUptimeChecker
{
  public function check(string $url): array
  {
    $start = microtime(true);

    try {
      $response = Http::timeout(10)->get($url);

      $responseTimeMs = (int) ((microtime(true) - $start) * 1000);
      $statusCode = $response->status();

      return [
        'status' => $statusCode >= 200 && $statusCode < 400 ? 'up' : 'down',
        'status_code' => $statusCode,
        'response_time_ms' => $responseTimeMs,
      ];
    } catch (Throwable $e) {
      return [
        'status' => 'down',
        'status_code' => null,
        'response_time_ms' => null,
      ];
    }
  }
}

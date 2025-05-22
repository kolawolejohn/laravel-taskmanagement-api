<?php

namespace App\Jobs;

use App\Services\AblyService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AblyPublishJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $event;
    protected array $data;

    public $tries = 3;
    public $backoff = 5; // in seconds

    public function __construct(string $event, array $data)
    {
        $this->event = $event;
        $this->data = $data;
    }

    public function handle(AblyService $ablyService)
    {
        $jobId = uniqid('job_', true);
        $startTime = now();

        Log::channel('joblog')->info("Job START", [
            'job' => 'AblyPublishJob',
            'id' => $jobId,
            'event' => $this->event,
            'data' => $this->data,
            'time' => $startTime->toDateTimeString(),
        ]);
        // $ablyService->publish($this->event, $this->data);
        try {
            // Edge case: If no data, bail early but still log
            if (empty($this->data)) {
                Log::channel('joblog')->warning("Job SKIPPED - no data", [
                    'job' => 'AblyPublishJob',
                    'id' => $jobId,
                ]);
                return;
            }

            $ablyService->publish($this->event, $this->data);

            $durationMs = now()->diffInMilliseconds($startTime);

            Log::channel('joblog')->info("Job SUCCESS", [
                'job' => 'AblyPublishJob',
                'id' => $jobId,
                'duration_ms' => $durationMs,
            ]);
        } catch (\Throwable $e) {
            Log::channel('joblog')->error("Job FAILED", [
                'job' => 'AblyPublishJob',
                'id' => $jobId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Rethrow to let Laravel handle retries/failures
            throw $e;
        }
    }
}

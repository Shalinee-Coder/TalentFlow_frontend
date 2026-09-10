<?php

namespace App\Jobs;

use App\Enums\ResumeStatus;
use App\Models\Resume;
use App\Services\ResumeProcessingService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessResumeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 10;

    public function __construct(
        public int $resumeId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(ResumeProcessingService $service): void
    {
        $resume = Resume::with(['candidate', 'applications.job'])->find($this->resumeId);

        if (!$resume) {
            Log::warning("ProcessResumeJob: Resume #{$this->resumeId} not found. Skipping.");
            return;
        }

        // Idempotency check: if already processed, skip
        if ($resume->processing_status === ResumeStatus::PROCESSED) {
            Log::info("ProcessResumeJob: Resume #{$this->resumeId} is already processed.");
            return;
        }

        Log::info("ProcessResumeJob: Starting processing for Resume #{$this->resumeId} (Attempt: {$this->attempts()})");

        $service->process($resume);

        Log::info("ProcessResumeJob: Successfully processed Resume #{$this->resumeId}");
    }

    /**
     * Handle a job failure.
     */
    public function failed(?Throwable $exception): void
    {
        Log::error("ProcessResumeJob: Permanently failed processing Resume #{$this->resumeId}: " . $exception?->getMessage());

        $resume = Resume::find($this->resumeId);
        if ($resume) {
            $resume->update([
                'processing_status' => ResumeStatus::FAILED->value,
            ]);
        }
    }
}

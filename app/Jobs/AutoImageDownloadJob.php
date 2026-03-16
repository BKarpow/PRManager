<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\ImageService;

class AutoImageDownloadJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $s
        ) {}

    /**
     * Execute the job.
     */
    public function handle(ImageService $service): void
    {
        $service->runOneJob($this->s);
    }
}

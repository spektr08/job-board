<?php

namespace App\Jobs;

use App\Mail\JobResponseCreated;
use App\Models\JobVacancy;
use App\Models\JobVacancyResponse;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotifyJobResponse implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public JobVacancyResponse $jobVacancyResponse;
    public JobVacancy $jobVacancy;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(JobVacancyResponse $jobVacancyResponse)
    {
        $this->jobVacancyResponse = $jobVacancyResponse;
        $this->jobVacancy = $jobVacancyResponse->jobVacancy;
    }

    public function middleware()
    {
        return [new RateLimited('notifyJobResponse')];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->jobVacancy->user->email)->send(new JobResponseCreated($this->jobVacancyResponse));
    }
}

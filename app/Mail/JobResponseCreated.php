<?php

namespace App\Mail;

use App\Models\JobVacancy;
use App\Models\JobVacancyResponse;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobResponseCreated extends Mailable
{
    use Queueable, SerializesModels;

    public JobVacancyResponse $jobVacancyResponse;
    public JobVacancy $jobVacancy;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(JobVacancyResponse $jobVacancyResponse)
    {
        $this->jobVacancyResponse = $jobVacancyResponse;
        $this->jobVacancy = $jobVacancyResponse->jobVacancy;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.response');
    }
}

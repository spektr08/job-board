<?php

namespace App\UseCases;

use App\Http\Requests\JobVacancyResponse\JobVacancyResponseRequest;
use App\Jobs\NotifyJobResponse;
use Carbon\Carbon;
use App\Models\JobVacancy;
use App\Models\JobVacancyResponse;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class JobsResponseService
{

    public function create(int $userId,int $job_id ,JobVacancyResponseRequest $request): JobVacancyResponse
    {
        /** @var User $user */
        $user = User::findOrFail($userId);
        $jobVacancy = $this->getJobVacancy($job_id);
        $jobVacancyResponse =  DB::transaction(function () use ($request, $user, $jobVacancy) {

            /** @var JobVacancyResponse $jobResponse */
            $jobResponse = JobVacancyResponse::make([
                'content' => $request['content'],
            ]);

            $jobResponse->user()->associate($user);
            $jobResponse->jobVacancy()->associate($jobVacancy);
            $jobResponse->saveOrFail();
            $user->update(['coins' => $user->coins - JobVacancyResponse::COST]);
            return $jobResponse;
        });
        $this->sendEmail($jobVacancyResponse);
        return $jobVacancyResponse;
    }

    public function remove(int $id,int $user_id): void
    {
        $jobVacancyResponse = $this->getJobVacancyResponse($id);
        if($jobVacancyResponse->user_id == $user_id){
            $jobVacancyResponse->delete();
        }else{
            throw new \DomainException('Unable to delete not your response');
        }
    }

    private function getJobVacancyResponse(int $id): JobVacancyResponse
    {
        return JobVacancyResponse::findOrFail($id);
    }
    private function getJobVacancy(int $id): JobVacancy
    {
        return JobVacancy::findOrFail($id);
    }

    public function sendEmail(JobVacancyResponse $jobVacancyResponse): void
    {

        NotifyJobResponse::dispatch($jobVacancyResponse);
    }

}

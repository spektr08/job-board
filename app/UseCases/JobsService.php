<?php

namespace App\UseCases;

use App\Http\Requests\JobVacancies\JobVacancyRequest;
use Carbon\Carbon;
use App\Models\JobVacancy;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class JobsService
{

    public function create(int $userId, JobVacancyRequest $request): JobVacancy
    {
        /** @var User $user */
        $user = User::findOrFail($userId);
        return DB::transaction(function () use ($request, $user) {

            /** @var JobVacancy $jobVacancy */
            $jobVacancy = JobVacancy::make([
                'title' => $request['title'],
                'description' => $request['description'],
                'status' => JobVacancy::STATUS_DRAFT,
            ]);

            $jobVacancy->user()->associate($user);
            $jobVacancy->saveOrFail();
            return $jobVacancy;
        });
    }

    public function edit(JobVacancyRequest $request, JobVacancy $jobVacancy)
    {
        if (!$jobVacancy->canBeChanged()) {
            throw new \DomainException('Unable to edit the jobVacancy.');
        }
        $jobVacancy->update([
            'title' => $request['title'],
            'description' => $request['description'],
        ]);
    }

    public function remove(int $id): void
    {
        $jobVacancy = $this->getJobVacancy($id);
        $jobVacancy->delete();
    }

    public function publish(int $id): void
    {
        $jobVacancy = $this->getJobVacancy($id);
        if(!$jobVacancy->user->canPublish()){
            throw new \DomainException("You can't publish");
        }
        $user = $jobVacancy->user;
        DB::transaction(function () use ($user, $jobVacancy) {
            $user->update(['coins' => $user->coins - JobVacancy::COST]);
            $jobVacancy->publish(Carbon::now());
        });
    }

    private function getJobVacancy(int $id): JobVacancy
    {
        return JobVacancy::findOrFail($id);
    }

}

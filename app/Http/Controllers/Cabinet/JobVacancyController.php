<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobVacancies\JobVacancyRequest;
use App\UseCases\JobsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JobVacancy;
use Illuminate\Support\Facades\Gate;

class JobVacancyController extends Controller
{
    private $service;

    public function __construct(JobsService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $jobs = JobVacancy::forUser(Auth::user())->paginate(10);
        return view('cabinet.job.dashboard', compact('jobs'));
    }

    public function responses(JobVacancy $jobVacancy)
    {
        $responses = $jobVacancy->responses()->paginate(10);
        return view('cabinet.job.responses', compact('responses'));
    }

    public function createForm(Request $request)
    {
        return view('cabinet.job.create', compact('request'));
    }

    public function editForm(Request $request,JobVacancy $jobVacancy)
    {
        $this->checkAccess($jobVacancy);
        return view('cabinet.job.edit', compact('request','jobVacancy'));
    }

    public function edit(JobVacancyRequest $request, JobVacancy $jobVacancy)
    {
        try {
            $this->checkAccess($jobVacancy);
            $this->service->edit($request, $jobVacancy);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('cabinet.vacancies');
    }

    public function publish(JobVacancy $jobVacancy)
    {
        try {
            if (!Gate::allows('publish-job')) {
                throw new \DomainException("You can't publish Job Vacancy need " . JobVacancy::COST ." coins");
            }
            $this->service->publish($jobVacancy->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('cabinet.vacancies');
    }
    public function delete(JobVacancy $jobVacancy)
    {
        $this->checkAccess($jobVacancy);
        $jobVacancy->delete();
        return redirect()->route('cabinet.vacancies');
    }

    public function store(JobVacancyRequest $request)
    {
        try {
            $this->service->create(Auth::id(), $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('cabinet.vacancies');

    }

    private function checkAccess(JobVacancy $jobVacancy): void
    {
        if (!Gate::allows('manage-own-job', $jobVacancy)) {
            abort(403);
        }
    }


}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobVacancyResponse\JobVacancyResponseRequest;
use App\Models\JobVacancy;
use App\Models\JobVacancyResponse;
use App\UseCases\JobsResponseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class MainController extends Controller
{
    private $service;

    public function __construct(JobsResponseService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $jobs = JobVacancy::published()->paginate(10);
        return view('welcome', compact('jobs'));
    }

    public function show(JobVacancy $jobVacancy)
    {
        return view('show', compact('jobVacancy'));
    }

    public function responseForm(JobVacancy $jobVacancy)
    {
        $this->canResponseAccess($jobVacancy);
        return view('response', compact('jobVacancy'));
    }

    public function response(JobVacancyResponseRequest $request,JobVacancy $jobVacancy)
    {
        try {
            if (!Gate::allows('response-job')) {
                throw new \DomainException("You can't publish Job Vacancy need " . JobVacancyResponse::COST . " coins");
            }
            $this->canResponseAccess($jobVacancy);
            $this->service->create(Auth::id(), $jobVacancy->id,$request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('index');
    }

    private function canResponseAccess(JobVacancy $jobVacancy)
    {
        if(!$jobVacancy->canResponse(Auth::id())){
            throw new \DomainException("Can't response to this jobVacancy");
        }
    }

}

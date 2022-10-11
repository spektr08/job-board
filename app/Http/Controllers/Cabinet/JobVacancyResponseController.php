<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\JobVacancyResponse;
use App\UseCases\JobsResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class JobVacancyResponseController extends Controller
{
    private $service;

    public function __construct(JobsResponseService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $responses = JobVacancyResponse::forUser(Auth::user())->paginate(10);
        return view('cabinet.job_response.index', compact('responses'));
    }


    public function delete(JobVacancyResponse $jobVacancyResponse)
    {
        $this->checkAccess($jobVacancyResponse);
        $this->service->remove($jobVacancyResponse->id, Auth::id());
        return redirect()->route('cabinet.responses');
    }

    private function checkAccess(JobVacancyResponse $jobVacancyResponse): void
    {
        if (!Gate::allows('manage-own-response', $jobVacancyResponse)) {
            abort(403);
        }
    }


}

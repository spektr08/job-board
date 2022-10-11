<?php

namespace App\Http\Controllers\Api;


use App\Http\Requests\JobVacancies\JobVacancyRequest;
use App\Http\Requests\JobVacancyResponse\JobVacancyResponseRequest;
use App\Http\Resources\JobVacanyResponseResorce;
use App\Models\JobVacancy;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Resources\JobVacanyResorce;
use App\Models\JobVacancyResponse;
use App\UseCases\JobsResponseService;
use App\UseCases\JobsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class JobController extends  BaseController
{


    private $service;
    private $responseService;

    public function __construct(JobsService $service, JobsResponseService $responseService)
    {
        $this->service = $service;
        $this->responseService = $responseService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $jobs = JobVacancy::published();

        if($request['filter_date'])
        {
            $date = new Carbon($request['filter_date']);
            $jobs->whereDate('published_at', '=', $date->toDateString());
        }

        if($request['sort']  and $request['sort'] == 'published_at'){
            $jobs->orderByDesc($request['sort']);
        }elseif($request['sort']  and $request['sort'] == 'responses'){
            $jobs->withCount('responses')->orderByDesc('responses_count', 'desc');
        }

        $jobs = $jobs->get();

        return $this->sendResponse(JobVacanyResorce::collection($jobs), 'Jobs retrieved successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $job = JobVacancy::find($id);

        if (is_null($job)) {
            return $this->sendError('Job not found.');
        }

        return $this->sendResponse(new JobVacanyResorce($job), 'Product retrieved successfully.');
    }

    public function edit(JobVacancyRequest $request, JobVacancy $jobVacancy)
    {
        try {
            $this->checkAccess($jobVacancy);
            $job = $this->service->edit($request, $jobVacancy);
        } catch (\DomainException $e) {
            return $this->sendResponse('', $e->getMessage());
        }
        return $this->sendResponse(new JobVacanyResorce($job), 'Edited');
    }

    public function publish(JobVacancy $jobVacancy)
    {
        try {
            if (!Gate::allows('publish-job')) {
                throw new \DomainException("You can't publish Job Vacancy need " . JobVacancy::COST ." coins");
            }
            $this->service->publish($jobVacancy->id);
        } catch (\DomainException $e) {
            return $this->sendResponse('', $e->getMessage());
        }
        return $this->sendResponse('', 'Job published successfully.');
    }
    public function delete(JobVacancy $jobVacancy)
    {
        $this->checkAccess($jobVacancy);
        $jobVacancy->delete();
        return $this->sendResponse('', 'Job deleted successfully.');
    }

    public function store(JobVacancyRequest $request)
    {
        try {
           $job = $this->service->create(Auth::id(), $request);
        } catch (\DomainException $e) {
            return $this->sendResponse('', $e->getMessage());
        }
        return $this->sendResponse(new JobVacanyResorce($job), 'Job created successfully.');

    }

    public function response(JobVacancyResponseRequest $request,JobVacancy $jobVacancy)
    {
        try {
            if (!Gate::allows('response-job')) {
                throw new \DomainException("You can't publish Job Vacancy need " . JobVacancyResponse::COST . " coins");
            }
            $this->canResponseAccess($jobVacancy);
            $response = $this->responseService->create(Auth::id(), $jobVacancy->id,$request);
        } catch (\DomainException $e) {
            return $this->sendResponse('', $e->getMessage());
        }
        return $this->sendResponse(new JobVacanyResponseResorce($response), 'Response created successfully.');
    }

    public function deleteResponse(JobVacancyResponse $jobVacancyResponse)
    {
        $this->checkAccessResponse($jobVacancyResponse);
        $this->responseService->remove($jobVacancyResponse->id, Auth::id());
        return $this->sendResponse('', 'Deleted');
    }

    private function canResponseAccess(JobVacancy $jobVacancy)
    {
        if(!$jobVacancy->canResponse(Auth::id())){
            throw new \DomainException("Can't response to this jobVacancy");
        }
    }

    private function checkAccessResponse(JobVacancyResponse $jobVacancyResponse): void
    {
        if (!Gate::allows('manage-own-response', $jobVacancyResponse)) {
            abort(403);
        }
    }

    private function checkAccess(JobVacancy $jobVacancy): void
    {
        if (!Gate::allows('manage-own-job', $jobVacancy)) {
            abort(403);
        }
    }

}

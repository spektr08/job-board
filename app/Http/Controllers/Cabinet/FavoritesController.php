<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\JobVacancy;
use App\Models\User;
use App\UseCases\FavoritesService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class FavoritesController extends Controller
{
    private $service;

    public function __construct(FavoritesService $service)
    {
        $this->service = $service;
    }

    public function addFavoriteUser(User $user)
    {
        try {
            $this->service->addUserFavorite(Auth::id(), $user->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'User is added to your favorites.');
    }

    public function addFavoriteJob(JobVacancy $jobVacancy)
    {
        try {
            $this->service->addJobFavorite(Auth::id(), $jobVacancy->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Job is added to your favorites.');
    }

    public function delete(Favorite $favorite)
    {
        try {
            $this->checkAccess($favorite);
            $favorite->delete();
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Advert is added to your favorites.');
    }

    private function checkAccess(Favorite $favorite): void
    {
        if (!Gate::allows('manage-own-favorite', $favorite)) {
            abort(403);
        }
    }

}

<?php

namespace App\UseCases;

use App\Models\JobVacancy;
use App\Models\Favorite;
use App\Models\User;

class FavoritesService
{

    public function addUserFavorite(int $userId,int $userFavoriteId): void
    {
        $user = $this->getUser($userId);
        $userFavorite = $this->getUser($userFavoriteId);
        if(!$userFavorite->canBeLikedByUser($user->id)){
            throw new \DomainException("You can't like");
        }
        $favorite = new Favorite();
        $favorite->user()->associate($user);
        $userFavorite->favorites()->save($favorite);
    }

    public function addJobFavorite(int $userId,int $jobVacancyId): void
    {
        $user = $this->getUser($userId);
        $jobVacancy = $this->getJobVacancy($jobVacancyId);
        if(!$jobVacancy->canBeLikedByUser($user->id)){
            throw new \DomainException("You can't like");
        }
        $favorite = new Favorite();
        $favorite->user()->associate($user);
        $jobVacancy->favorites()->save($favorite);
    }

    public function removeFavorite($id): void
    {
        $favorite = $this->getFavorite($id);
        $favorite->delete();
    }

    private function getUser(int $userId): User
    {
        return User::findOrFail($userId);
    }

    private function getJobVacancy(int $jobVacancyId): JobVacancy
    {
        return JobVacancy::findOrFail($jobVacancyId);
    }

    private function getFavorite(int $favoriteId): Favorite
    {
        return Favorite::findOrFail($favoriteId);
    }



}

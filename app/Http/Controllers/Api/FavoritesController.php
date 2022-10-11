<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Resources\FavoriteResorce;
use App\Models\Favorite;
use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class FavoritesController extends  BaseController
{

   public function indexUsers()
   {
       $favorites = Favorite::where('user_id', Auth::id())->where('favoritable_type', User::class)->get();
       return $this->sendResponse(new FavoriteResorce($favorites), 'Favorites retrieved successfully.');
   }

   public function indexJobs()
   {
       $favorites = Favorite::where('user_id', Auth::id())->where('favoritable_type', JobVacancy::class)->get();
       return $this->sendResponse(new FavoriteResorce($favorites), 'Favorites retrieved successfully.');
   }
}

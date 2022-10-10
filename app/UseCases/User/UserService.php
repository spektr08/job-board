<?php

namespace App\UseCases\User;


use App\Models\User;
use Carbon\Carbon;

class UserService
{

    private $config;

    public function __construct(array $config)
    {
       $this->config= $config;
    }

    public function dayCoins(User  $user): void
    {
        $date = Carbon::parse($user->coins_date);
        if(!$date->isToday() or is_null($user->coins_date))
        {
            $user->update([
                'coins'=>$user->coins + $this->config['dayCoins'],
                'coins_date' => Carbon::now()
            ]);
        }

    }

}

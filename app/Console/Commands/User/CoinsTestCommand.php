<?php

namespace App\Console\Commands\User;

use App\Models\User;
use App\UseCases\User\UserService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CoinsTestCommand extends Command
{
    protected $signature = 'user:coinsTest';

    protected $description = 'Every days coins';

    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
        parent::__construct();
    }

    public function handle(): bool
    {
        foreach (User::cursor() as $user){
            $user->update([
                'coins'=>$user->coins + 5,
                'coins_date' => Carbon::now()
            ]);
        }
        return true;
    }
}

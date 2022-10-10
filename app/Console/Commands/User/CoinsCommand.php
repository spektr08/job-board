<?php

namespace App\Console\Commands\User;

use App\Models\User;
use App\UseCases\User\UserService;
use Illuminate\Console\Command;

class CoinsCommand extends Command
{
    protected $signature = 'user:coins';

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
            $this->service->dayCoins($user);
        }
        return true;
    }
}

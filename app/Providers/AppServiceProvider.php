<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\UseCases\User\UserService;
use Illuminate\Contracts\Foundation\Application;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(UserService::class, function (Application $app) {
            $config = $app->make('config')->get('user');
            return new UserService($config);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

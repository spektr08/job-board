<?php

namespace App\Providers;

use App\Models\Favorite;
use App\Models\JobVacancyResponse;
use Illuminate\Support\Facades\Gate;
use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerPermissions();

        //
    }

    private function registerPermissions(): void
    {

        Gate::define('admin', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('publish-job', function (User $user) {
            return $user->coins >= JobVacancy::COST;
        });

        Gate::define('response-job', function (User $user) {
            return $user->coins >= JobVacancyResponse::COST;
        });

        Gate::define('manage-own-job', function (User $user, JobVacancy $jobVacancy) {
            return $jobVacancy->user_id === $user->id;
        });

        Gate::define('manage-own-response', function (User $user, JobVacancyResponse $jobVacancyResponse) {
            return $jobVacancyResponse->user_id === $user->id;
        });

        Gate::define('manage-own-favorite', function (User $user, Favorite $favorite) {
            return $favorite->user_id === $user->id;
        });

    }
}

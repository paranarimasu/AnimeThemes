<?php

namespace App\Providers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Horizon::routeMailNotificationsTo(Config::get('mail.from.address'));

        Horizon::night();
    }

    /**
     * Register the Horizon gate.
     *
     * This gate determines who can access Horizon in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewHorizon', function (User $user) {
            $horizon_team = Team::find(Config::get('horizon.team'));

            return $user->isCurrentTeam($horizon_team);
        });
    }
}
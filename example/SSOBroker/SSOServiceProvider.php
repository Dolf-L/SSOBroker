<?php

namespace Dolf\SSO\example\SSOBroker;

use Dolf\SSO\Managers\UserManager;
use Dolf\SSO\Repositories\UserRepository;
use Dolf\SSO\SSOBroker;
use Dolf\SSO\SSOLaravel;
use Dolf\SSO\SSOValidator;
use Illuminate\Support\ServiceProvider;

class SSOServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Dolf\SSO\SSOValidator', function ($app) {
            /** @var \Illuminate\Contracts\Foundation\Application $app */
            return new SSOValidator($app->make('Illuminate\Contracts\Validation\Factory'));
        });

        $this->app->bind('Dolf\SSO\Repositories\UserRepository', function ($app) {
            /** @var \Illuminate\Contracts\Foundation\Application $app */
            return new UserRepository($app->make('App\Models\User'));
        });

        $this->app->bind('Dolf\SSO\Managers\UserManager', function ($app) {
            /** @var \Illuminate\Contracts\Foundation\Application $app */
            return new UserManager($app->make('App\Models\User'));
        });

        $this->app->bind('Dolf\SSO\SSOBroker', function () {
            /** @var \Illuminate\Contracts\Foundation\Application $app */
            return new SSOBroker(env('BROKER_URL'), env('API_KEY'));
        });

        $this->app->bind('Dolf\SSO\SSOLaravel', function ($app) {
            /** @var \Illuminate\Contracts\Foundation\Application $app */
            return new SSOLaravel(
                $app->make('Dolf\SSO\SSOBroker'),
                $app->make('Dolf\SSO\Managers\UserManager'),
                $app->make('Dolf\SSO\Repositories\UserRepository'),
                $app->make('Dolf\SSO\SSOValidator'),
                $app->make('Illuminate\Contracts\Logging\Log')
            );
        });
    }
}

<?php

namespace Acme\Providers;

use Illuminate\Auth\Guard;
use Acme\Services\UserProvider;
use Acme\Services\PasswordUpgrader;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app['auth.upgrader'] = $this->app->share(function ($app)
        {
            // Instantiate the PasswordUpgrader and inject
            // the Hasher and Config classes into it.
            return new PasswordUpgrader($app['hash'], $app['config']);
        });
    }

    public function boot()
    {
        $this->app['auth']->extend('legacy', function ($app)
        {
            // Get the model name from the auth config file
            // file and instantiate a new Hasher and our
            // PasswordUpgrader from the container.
            $model = $app->config['auth.model'];
            $hash = $app['hash'];
            $upgrader = $app['auth.upgrader'];

            // Instantiate our own UserProvider class.
            $provider = new UserProvider($hash, $upgrader, $model);

            // Return a new Guard instance and pass our
            // UserProvider class into its constructor.
            return new Guard($provider, $app['session.store']);
        });
    }
}
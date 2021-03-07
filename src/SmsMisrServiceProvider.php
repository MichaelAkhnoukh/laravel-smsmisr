<?php

namespace Caishni\SmsMisr;

use Illuminate\Support\ServiceProvider;

class SmsMisrServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/sms-misr.php' => config_path('sms-misr.php')
            ], 'config');
        }

        $this->app->bind('smsmisr', function ($app) {
            return new SmsMisr($app->config['sms-misr']);
        });
    }

    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/sms-misr.php',
            'sms-misr');
    }
}
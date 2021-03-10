<?php

namespace Caishni\SmsMisr;

use Illuminate\Support\ServiceProvider;
use Caishni\SmsMisr\Contracts\SmsMisr as SmsMisrContract;

class SmsMisrServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/sms-misr.php',
            'sms-misr');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/sms-misr.php' => config_path('sms-misr.php')
            ], 'config');
        }
    }

    public function register()
    {
        $this->app->bind(SmsMisrContract::class, SmsMisr::class);
    }
}
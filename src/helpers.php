<?php

use Caishni\SmsMisr\Contracts\SmsMisr as SmsMisrContract;

if (!function_exists('sms')) {
    function sms(): SmsMisrContract
    {
        return app(SmsMisrContract::class);
    }
}
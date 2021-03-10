<?php

use Caishni\SmsMisr\SmsMisr;

if (!function_exists('sms')) {
    function sms(): SmsMisr
    {
        return app(SmsMisr::class);
    }
}
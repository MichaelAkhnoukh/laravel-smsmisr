<?php

namespace Caishni\SmsMisr\Facades;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Caishni\SmsMisr\SmsMisr to(string|array $numbers)
 * @method static \Caishni\SmsMisr\SmsMisr from(string $sender)
 * @method static \Caishni\SmsMisr\SmsMisr message(string $message)
 * @method static \Caishni\SmsMisr\SmsMisr delay(Carbon $delay)
 * @method static \Caishni\SmsMisr\SmsMisr language(string $lang)
 * @method static object send()
 */

class SmsMisr extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'smsmisr';
    }
}
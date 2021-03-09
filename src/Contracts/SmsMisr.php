<?php

namespace Caishni\SmsMisr\Contracts;

use Illuminate\Support\Carbon;

interface SmsMisr
{
    /**
     * @param string|array $recipients
     * @return SmsMisr
     */
    public function to($recipients): SmsMisr;

    /**
     * @param string $sender
     * @return SmsMisr
     */
    public function from(string $sender): SmsMisr;

    /**
     * @param string $message
     * @return SmsMisr
     */
    public function message(string $message): SmsMisr;

    /**
     * @param Carbon $date
     * @return SmsMisr
     */
    public function delay(Carbon $date): SmsMisr;

    /**
     * @param string $lang
     * @return SmsMisr
     */
    public function language(string $lang): SmsMisr;

    /**
     * Send request
     *
     * @return object
     * @throws \Exception
     */
    public function send(): object;
}
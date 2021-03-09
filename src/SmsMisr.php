<?php

namespace Caishni\SmsMisr;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Caishni\SmsMisr\Contracts\SmsMisr as SmsMisrContract;

class SmsMisr implements SmsMisrContract
{

    /** @var PendingRequest */
    protected $httpClient;

    /** @var string */
    protected $username;

    /** @var string */
    protected $password;

    /** @var string */
    protected $sender;

    /** @var string */
    protected $phoneNumbers;

    /** @var string */
    protected $message;

    /** @var string */
    protected $delayDate;

    /** @var int */
    protected $language;

    const ALLOWED_LANGUAGES = [
        'en' => 1,
        'ar' => 2,
        'unicode' => 3
    ];

    public function __construct(PendingRequest $httpClient)
    {
        $this->validateConfiguration(config('sms-misr'));

        $this->httpClient = $httpClient;

        $this->httpClient->asForm()->baseUrl(config('sms-misr.endpoint'));
    }

    /**
     * @inheritDoc
     */
    public function to($recipients): SmsMisrContract
    {
        if (is_array($recipients)) {
            $this->phoneNumbers = implode(',', $recipients);
        } elseif (is_string($recipients)) {
            $this->phoneNumbers = $recipients;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function from(string $sender): SmsMisrContract
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function message(string $message): SmsMisrContract
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function delay(Carbon $date): SmsMisrContract
    {
        $this->delayDate = $date->format('Y-m-d-H-i');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function language(string $lang): SmsMisrContract
    {
        if (is_null($language = Arr::get(self::ALLOWED_LANGUAGES, $lang)))
            throw new \InvalidArgumentException("Unsupported language is set");

        $this->language = $language;
        return $this;
    }

    /**
     * prepares the request before sending
     * @return void
     * @throws \Exception
     */
    protected function validateRequest(): void
    {
        if (!isset($this->language))
            throw new \InvalidArgumentException("Language is not set");

        if (!isset($this->message))
            throw new \InvalidArgumentException("Message is not set");

        if (empty($this->phoneNumbers))
            throw new \InvalidArgumentException("Phone number is not set");
    }

    /**
     * Validates and sets package configuration variables
     *
     * @param array $config
     * @throws \Exception
     */
    protected function validateConfiguration(array $config): void
    {
        if (!isset($config['sender']))
            throw new \Exception("SMSMISR_API_SENDER is not set");

        if (!isset($config['username']))
            throw new \Exception("SMSMISR_API_USERNAME is not set");

        if (!isset($config['password']))
            throw new \Exception("SMSMISR_API_PASSWORD is not set");

        $this->sender = config('sms-misr.sender');
        $this->username = config('sms-misr.username');
        $this->password = config('sms-misr.password');
    }

    /**
     * @inheritDoc
     */
    public function send(): object
    {
        $this->validateRequest();

        $request = [
            'username' => $this->username,
            'password' => $this->password,
            'sender' => $this->sender,
            'language' => $this->language,
            'message' => $this->message,
            'mobile' => $this->phoneNumbers
        ];

        !isset($this->delayDate) ?: $request['DelayUntil'] = $this->delayDate;

        return $this->httpClient->post('', $request)->object();
    }

}
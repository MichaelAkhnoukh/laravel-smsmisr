<?php

namespace Caishni\SmsMisr;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class SmsMisr
{

    /** @var \Illuminate\Http\Client\PendingRequest */
    protected $httpClient;

    /** @var array */
    protected $request;

    /** @var string */
    protected $sender;

    /** @var array */
    protected $phoneNumbers = [];

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

    public function __construct(array $config)
    {
        $this->validate($config);

        $this->sender = $config['sender'];

        $this->request['username'] = $config['username'];
        $this->request['password'] = $config['password'];
        $this->request['sender'] = $this->sender;

        $this->httpClient = Http::asForm()->baseUrl($config['endpoint']);
    }

    /**
     * @param string|array $numbers
     * @return $this
     */
    public function to($numbers): SmsMisr
    {
        if (is_array($numbers)) {
            $this->phoneNumbers = array_merge($this->phoneNumbers, $numbers);
        } elseif (is_string($numbers)) {
            $this->phoneNumbers = array_merge($this->phoneNumbers, [$numbers]);
        }

        return $this;
    }

    /**
     * @param string $sender
     * @return $this
     */
    public function from(string $sender): SmsMisr
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function message(string $message): SmsMisr
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param Carbon $date
     * @return $this
     */
    public function delay(Carbon $date): SmsMisr
    {
        $this->delayDate = $date->format('Y-m-d-H-i');
        return $this;
    }

    /**
     * @param string $lang
     * @return $this
     */
    public function language(string $lang): SmsMisr
    {
        if (is_null($language = Arr::get(self::ALLOWED_LANGUAGES, $lang)))
            throw new \InvalidArgumentException("Unsupported language is set");

        $this->language = $language;
        return $this;
    }

    /**
     * prepares the request before sending
     * @return void
     */
    protected function prepareRequest(): void
    {
        if (! isset($this->language))
            throw new \InvalidArgumentException("Language is not set");

        if (!isset($this->message))
            throw new \InvalidArgumentException("Message is not set");

        if (empty($this->phoneNumbers))
            throw new \InvalidArgumentException("Phone number is not set");

        $this->request['language'] = $this->language;
        $this->request['message'] = $this->message;
        $this->request['mobile'] = implode(',', $this->phoneNumbers);

        is_null($this->delayDate) ?: $this->request['DelayUntil'] = $this->delayDate;
    }

    /**
     * Validates package configuration variables
     *
     * @param $config
     * @throws \Exception
     */
    protected function validate($config): void
    {
        if (!isset($config['sender']))
            throw new \Exception("SMSMISR_API_SENDER is not set");

        if (!isset($config['username']))
            throw new \Exception("SMSMISR_API_USERNAME is not set");

        if (!isset($config['password']))
            throw new \Exception("SMSMISR_API_PASSWORD is not set");
    }

    /**
     * @return object
     */
    public function send(): object
    {
        $this->prepareRequest();

        return $this->httpClient->post('', $this->request)->object();
    }

}
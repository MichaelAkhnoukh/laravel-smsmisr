<?php

return [
    'endpoint' => env('SMSMISR_API_ENDPOINT', 'https://smsmisr.com/api/v2/?'),
    'username' => env('SMSMISR_API_USERNAME'),
    'password' => env('SMSMISR_API_PASSWORD'),
    'sender' => env('SMSMISR_API_SENDER')
];
<?php

const SENDER_TEST = 'test';
const SENDER_SMSAERO = 'smsaero';

return [
    'path' => env('SMS_VERIFACTION_PATH', 'api/v1'),
    'type' => env('SMS_VERIFACTION_TYPE', ''),
    'sender' => env('SMS_SENDER', SENDER_TEST),
    'codeLength' => env('SMS_VERIFACTION_CODELENGTH', 4),
    'codeLifetime' => env('SMS_VERIFACTION_CODELIFETIME', 60),
    'smsaero-email' => env('SMS_VERIFACTION_SMSAERO_EMAIL', null),
    'smsaero-apikey' => env('SMS_VERIFACTION_SMSAERO_APIKEY', null),
];

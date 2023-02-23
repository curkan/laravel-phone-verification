<?php

namespace Gogain\LaravelPhoneVerification\Senders;

use Gogain\LaravelPhoneVerification\Contracts\SenderInterface;

/**
 * Class Sender
 * @package Hizbul\SmsVerification
 */
class TestSender implements SenderInterface
{

    /**
     * Expected HTTP status for successful SMS sending request
     */
    const EXPECTED_HTTP_STATUS = 201;

    /**
     * Username for Onnorokomsms.com API
     * @var string
     */
    private $userName;

    /**
     * API password
     * @var string
     */
    private $password;

     /**
     * Sender constructor
     * @throws ConfigException
     */
    public function __construct()
    {
        $this->userName = config('sms-verification.username');

        $this->password = config('sms-verification.password');
    }
    /**
     * Send SMS via Onnorokomsms.com API
     * @param string $to
     * @param string $text
     * @return bool
     * @throws SenderException
     */
    public function send($to, $text, $code)
    {
        return $this->sendMessageTest($to, $text, "FREE SIGN", $code);
    }

    public function sendMessageTest($number, $text, $channel, $code, $dateSend = null, $callbackUrl = null) {

        $response = json_decode('{
            "success": true,
            "data": [
                {
                    "id": 1,
                    "from": "SMS Aero",
                    "number": "'.$number.'",
                    "text": "'.$text.'",
                    "code": "'.$code.'",
                    "status": 0,
                    "extendStatus": "queue",
                    "channel": "FREE SIGN",
                    "cost": 1.95,
                    "dateCreate": 1510656981,
                    "dateSend": 1510656981
                }
                ],
                "message": null
            }');

        return response()->json([
            $response
        ], 201)->setStatusCode(201);
    }
}

<?php

namespace Gogain\LaravelPhoneVerification\Http\Controllers;

use Exception;
use Gogain\LaravelPhoneVerification\CodeProcessor;
use Gogain\LaravelPhoneVerification\Senders\SmsAeroSender;
use Gogain\LaravelPhoneVerification\SmsVerification;
use Gogain\LaravelPhoneVerification\Senders\TestSender;
use Symfony\Component\HttpFoundation\Request;

class SmsController
{
    public function send(Request $request)
    {
        if (is_null($request->get('phone_number'))) {
            throw new Exception('Phone field cannot be empty', 0, null);
        }

        $result = SmsVerification::sendCode($request->get('phone_number'), $this->getSender());
        $now = time();

        return response()->json([
            'result' => $result,
            'codeLifetime' => CodeProcessor::getLifetime(),
            'codeExpired' => $now + CodeProcessor::getLifetime(),
        ], 201);
    }

    public function checkCode($code, $phoneNumber)
    {
        if (is_null($code)) {
            throw new Exception('Code field cannot be empty', 0, null);
        }

        if (is_null($phoneNumber)) {
            throw new Exception('Phone field cannot be empty', 0, null);
        }

        $result = SmsVerification::checkCode($code, $phoneNumber);
        return $result; 
    }

    private function getSender() {
        $senderConfig = config('sms-verification.sender');

        switch ($senderConfig) {
            case 'test':
                return new TestSender();
                break;
            case 'smsaero':
                return new SmsAeroSender();
                break;
            default:
                return new TestSender();
                break;
        }
        
    }
}

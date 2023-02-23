<?php

namespace Gogain\LaravelPhoneVerification\Http\Controllers;

use Gogain\LaravelPhoneVerification\CodeProcessor;
use Gogain\LaravelPhoneVerification\Senders\SmsAeroSender;
use Gogain\LaravelPhoneVerification\SmsVerification;
use Gogain\LaravelPhoneVerification\Senders\TestSender;
use Symfony\Component\HttpFoundation\Request;

class SmsController
{
    public function send(Request $request)
    {
        $result = SmsVerification::sendCode($request->get('phone_number'), $this->getSender());

        return response()->json([
            'result' => $result,
            'codeExpired' => CodeProcessor::getLifetime(),
        ], 201);
    }

    public function checkCode($code, $phoneNumber)
    {
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

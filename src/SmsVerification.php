<?php

namespace Gogain\LaravelPhoneVerification;

use Gogain\LaravelPhoneVerification\Contracts\SenderInterface;
use Gogain\LaravelPhoneVerification\Models\Phone;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

/**
 * Class SmsVerification
 * @package Hizbul\SmsVerification
 */
class SmsVerification
{

    /**
     * Send code
     * @param $phoneNumber
     * @return array
     */
    public static function sendCode($phoneNumber, SenderInterface $sender)
    {
        try {
            static::validatePhoneNumber($phoneNumber);
            $chachedPhone = Cache::get(static::trimPhoneNumber($phoneNumber));

            if (!is_null($chachedPhone)) {
                throw ValidationException::withMessages(['You cannot send a message more often than allowed']);
            }

            // $phone = Phone::where('phone', $phoneNumber);
            // $resendCode = false;
            //
            // if (count($phone->get()) !== 0) {
            //     if (count($phone->where('status', false)->get()) === 1) {
            //         $resendCode = true;
            //     } else {
            //         throw ValidationException::withMessages(['Phone already verified']);
            //     }
            // }

            $codeProcessor = new CodeProcessor();

            $code = $codeProcessor->generateCode($phoneNumber);

            $text = $code . ' - ваш код подтверждения';

            $success = $sender->send($phoneNumber, $text, $code);

            // if ($resendCode) {
            //     $phone->update(['code' => $code]);
            // } else {
                // $phone = Phone::create([
                //     'user_id' => 0,
                //     'code' => $code,
                //     'status' => 0,
                //     'phone' => $phoneNumber
                // ]);
            // }

        } catch (\Exception $e) {
            $description = $e->getMessage();

            throw ValidationException::withMessages([$description]);
        }

        return $success;
    }

    /**
     * Check code
     * @param $code
     * @param $phoneNumber
     * @return array
     */
    public static function checkCode($code, $phoneNumber)
    {
        try {
            if (!is_numeric($code)) {
                throw ValidationException::withMessages(['Incorrect code was provided']);
            }

            static::validatePhoneNumber($phoneNumber);
            $codeProcessor = new CodeProcessor();

            $success = $codeProcessor->validateCode($code, $phoneNumber);

            if (!$success) {
                return response()->json([
                    'data' => 'Validation code error'
                ], 404)->setStatusCode(404);
            }

            return response()->json([
                'data' => 'Success validation'
            ], 201)->setStatusCode(201);

        } catch (\Exception $e) {
            $description = $e->getMessage();
            throw ValidationException::withMessages([$description]);
        }

    }

    /**
     * Validate phone number
     * @param string $phoneNumber
     * @throws ValidationException
     */
    protected static function validatePhoneNumber($phoneNumber){
        $patterns = [
            "\?:\+88|01)?(?:\d{11}|\d{13}", // BD
            "\+?[2-9]\d{9,}", // International
        ];
        if (!@preg_match("/^(" . implode('|', $patterns) . ")\$/", $phoneNumber)) {
            throw ValidationException::withMessages(['Incorrect phone number was provided']);
        }
    }
    /**
     * @param $phoneNumber
     * @return string
     */
    public static function trimPhoneNumber($phoneNumber){
        return trim(ltrim($phoneNumber, '+'));
    }


}

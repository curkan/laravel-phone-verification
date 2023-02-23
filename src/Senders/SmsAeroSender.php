<?php

namespace Gogain\LaravelPhoneVerification\Senders;

use Gogain\LaravelPhoneVerification\Contracts\SenderInterface;
use Illuminate\Support\Facades\Response as Response;

/**
 * Class Sender
 * @package Hizbul\SmsVerification
 */
class SmsAeroSender implements SenderInterface
{

    const URL_SMSAERO_API = 'https://gate.smsaero.ru/v2';

    private $email = ''; //Ваш логин|email
    private $api_key = ''; //Ваш api_key можно получить по адресу https://smsaero.ru/cabinet/settings/apikey/
    private $sign = 'SMS Aero'; //Подпись по умолчанию
    /**
     * Expected HTTP status for successful SMS sending request
     */
    const EXPECTED_HTTP_STATUS = 201;
     /**
     * Sender constructor
     * @throws ConfigException
     */
    public function __construct()
    {
        $this->email = config('sms-verification.smsaero-email');
        $this->api_key = config('sms-verification.smsaero-apikey');
    }

    /**
     * Тестовый метод, для проверки авторизации пользователя
     * @return array
     */
    public function auth(){
        return json_decode(self::curl_post(self::URL_SMSAERO_API . '/auth'));
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
        return $this->sendMessage($to, $text, "FREE SIGN", $code);
        // return $this->sendMessageTest($to, $text, "FREE SIGN", $code);
    }

    public function sendMessage($number, $text, $channel, $code, $dateSend = null, $callbackUrl = null){
        return json_decode(self::curl_post(self::URL_SMSAERO_API . '/sms/send/', [
            is_array($number) ? 'numbers' : 'number' => $number,
            'sign' => $this->sign,
            'text' => $text,
            'code' => $code,
            'channel' => $channel,
            'dateSend' => $dateSend,
            'callbackUrl' => $callbackUrl
        ]), true);
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
    /**
     * Формирование curl запроса
     * @param $url
     * @param $post
     * @param $options
     * @return mixed
     */
    private function curl_post($url, array $post = NULL, array $options = array()){
        $defaults = array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POSTFIELDS => http_build_query($post),
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_USERPWD => $this->email . ":" . $this->api_key,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
        );

        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        if (!$result = curl_exec($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }

}

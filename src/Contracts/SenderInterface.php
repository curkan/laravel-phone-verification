<?php

namespace Gogain\LaravelPhoneVerification\Contracts;

use Gogain\LaravelPhoneVerification\Exceptions\SenderException;

/**
 * Interface SenderInterface
 * @package Hizbul\SmsVerification
 */
interface SenderInterface
{
    /**
     * Send SMS via Phone.com API
     * @param string $to
     * @param string $text
     * @return bool
     * @throws SenderException
     */
    public function send($to, $text, $code);

}

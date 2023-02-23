<?php

namespace Gogain\LaravelPhoneVerification;

use Exception;
use Gogain\LaravelPhoneVerification\Models\Phone;
use Illuminate\Support\Facades\Cache;
use Hizbul\SmsVerification\Exceptions\GenerateCodeException;
use Hizbul\SmsVerification\Exceptions\ValidateCodeException;
use Illuminate\Validation\ValidationException;

/**
 * Class CodeProcessor
 * @package Hizbul\SmsVerification
 */
class CodeProcessor
{

    /**
     * Prefix for cache keys
     * @var string
     */
    private $cachePrefix = '';

    /**
     * Code length
     * @var int
     */
    private $codeLength = 4;

    /**
     * Lifetime of codes in minutes
     * @var int
     */
    private $secondsLifetime = 1;

    /**
     * Singleton instance
     * @var
     */
    private static $instance;

    /**
     * CodeProcessor constructor
     * @throws ConfigException
     */
    public function __construct()
    {
        $this->codeLength = config('sms-verification.codeLength', $this->codeLength);
        $this->secondsLifetime = config('sms-verification.codeLifetime', $this->secondsLifetime);
    }

    /**
     * Generate code, save it in Cache, return it
     * TODO Add Cache tags support
     * @param string $phoneNumber
     * @return string
     * @throws GenerateCodeException
     */
    public function generateCode($phoneNumber)
    {
        try {
            $randomFunction = 'random_int'; // This function is better, but it was added in PHP7
            if (!function_exists($randomFunction)){
                $randomFunction = 'mt_rand';
            }
            $code = $randomFunction(pow(10, $this->codeLength - 1), pow(10, $this->codeLength) - 1);
            Cache::put($this->cachePrefix . $code, $this->trimPhoneNumber($phoneNumber), now()->addSeconds($this->secondsLifetime));
            Cache::put($this->cachePrefix . $this->trimPhoneNumber($phoneNumber), 1, now()->addSeconds($this->secondsLifetime));
        } catch (\Exception $e){
            throw new Exception('Code generation failed', 0, $e);
        }

        return $code;
    }

    /**
     * Check code in Cache
     * @param string $code
     * @param string $phoneNumber
     * @return bool
     * @throws ValidateCodeException
     */
    public function validateCode($code, $phoneNumber)
    {
        // $phoneCode = Phone::where('code', $code)
        //     ->where('phone', $phoneNumber)
        //     ->where('status', false);
        //
        // $dbCode = $phoneCode->get();

        //
        // if (count($dbCode) === 0) {
        //     return false;
        // }

        $codeValue = Cache::get($this->cachePrefix . $code);

        if (is_null($codeValue)) {
            // $phoneCode->update(['code' => 0]);
            Cache::forget($this->cachePrefix . $code);
            throw new Exception('Code expired', 0, null);
        }
        Cache::forget($this->cachePrefix . $code);

        return true;
        // return $phoneCode->update(['status' => true]);
    }

    /**
     * @param $phoneNumber
     * @return string
     */
    private function trimPhoneNumber($phoneNumber){
        return trim(ltrim($phoneNumber, '+'));
    }

    /**
     * @return int Seconds
     */
    public static function getLifetime(){
        return config('sms-verification.codeLifetime');
    }

}

<?php

namespace Gogain\LaravelPhoneVerification\Test;

use Gogain\LaravelPhoneVerification\Models\Phone;
use Illuminate\Support\Facades\Config as FacadesConfig;
use Orchestra\Testbench\Foundation\Config;

class SmsTest extends FeatureTestCase
{
    /**
     * @test
     */
    public function test_send_sms()
    {
        FacadesConfig::set('sms-verification.username', 'test');
        FacadesConfig::set('sms-verification.password', 'test');
        FacadesConfig::set('sms-verification.codeLength', 4);
        FacadesConfig::set('sms-verification.codeLifetime', 60);

        FacadesConfig::set('sms-verification.smsaero-email', 'example@gmail.com');
        FacadesConfig::set('sms-verification.smsaero-apikey', 'testtest123123');

        $response = $this->postJson(route('sms-verification', ['phone_number' => '79009009000']));
        dd($response);

        $response->assertStatus(201);
    }

    /**
     * @test
     */
    public function test_code_check()
    {
        FacadesConfig::set('sms-verification.username', 'test');
        FacadesConfig::set('sms-verification.password', 'test');
        FacadesConfig::set('sms-verification.codeLength', 4);
        FacadesConfig::set('sms-verification.codeLifetime', 10);
        FacadesConfig::set('sms-verification.sender', 'test');

        FacadesConfig::set('sms-verification.smsaero-email', 'example@gmail.com');

        FacadesConfig::set('sms-verification.smsaero-apikey', 'testtest123123');
        $sendCode = $this->postJson(route('sms-verification', ['phone_number' => '79009009000']));
        $code = $sendCode['result']['original'][0]['data'][0]['code'];

        $phone = Phone::factory(['code' => $code, 'phone' => 79009009000])
            ->count(1)
            ->create();

        $response = $this->postJson(route('sms-verification.check', [
            'code' => $code,
            'number' => '79009009000'
        ]));

        $response->assertStatus(201);
    }

    /**
     * @test
     */
    public function test_code_expired()
    {
        FacadesConfig::set('sms-verification.username', 'test');
        FacadesConfig::set('sms-verification.password', 'test');
        FacadesConfig::set('sms-verification.codeLength', 4);
        FacadesConfig::set('sms-verification.codeLifetime', 1);

        FacadesConfig::set('sms-verification.smsaero-email', '79009009000');

        FacadesConfig::set('sms-verification.smsaero-apikey', 'testtest123123');
        $sendCode = $this->postJson(route('sms-verification', ['phone_number' => '79009009000']));
        $code = $sendCode['result']['original'][0]['data'][0]['code'];
        sleep(2);

        $phone = Phone::factory(['code' => $code, 'phone' => 79009009000])
            ->count(1)
            ->create();

        $response = $this->postJson(route('sms-verification.check', [
            'code' => $code,
            'number' => '79009009000'
        ]));

        $response->assertStatus(422);
    }
}

<?php

namespace Gogain\LaravelPhoneVerification\Test;

use PHPUnit\Framework\TestCase;
use Gogain\LaravelPhoneVerification\ExampleService;

class ExampleServiceTest extends TestCase
{
    /**
     * @test
     */
    public function it_gets_some_result()
    {
        $sut = new ExampleService;
        $this->assertEquals('bar', $sut->getSomeResult());
    }
}

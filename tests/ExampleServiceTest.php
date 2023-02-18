<?php

namespace Curkan\LaravelPhoneVerification\Test;

use PHPUnit\Framework\TestCase;
use Curkan\LaravelPhoneVerification\ExampleService;

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

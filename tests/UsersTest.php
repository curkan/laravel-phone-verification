<?php

namespace Gogain\LaravelPhoneVerification\Test;

use Gogain\LaravelPhoneVerification\Models\User;

class UsersTest extends FeatureTestCase
{
    /**
     * @test
     */
    public function it_gets_all_items()
    {
        $user = User::forceCreate([
            'name' => 'Name 1',
            'email' => 'necit@test.tu',
            'password' => '123123',
        ]);

        $response = $this->getJson(route('users'));

        $response->assertStatus(200);

        $response->assertExactJson([
            'users' => [
                ['name' => 'Name 1'],
            ]
        ]);
    }
}

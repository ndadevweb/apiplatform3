<?php

namespace App\Tests\Functional;

use Zenstruck\Foundry\Test\ResetDatabase;

class UserResourceTest extends ApiTestCase
{
    use ResetDatabase;

    public function testPostToCreateUser(): void
    {
        $this->browser()
            ->post('/api/users', [
                'json' => [
                    'username' => 'draggin_in_the_morning',
                    'email' => 'draggin_in_the_morning@coffee.com',
                    'password' => 'password'
                ]
            ])
            ->assertStatus(201)
            ->post('/login', [
                'json' => [
                    'email' => 'draggin_in_the_morning@coffee.com',
                    'password' => 'password'
                ]
            ])
            ->assertSuccessful()
        ;
    }
}
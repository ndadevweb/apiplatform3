<?php

namespace App\Tests\Functional;

use App\Entity\DragonTreasure;
use App\Factory\DragonTreasureFactory;
use App\Factory\UserFactory;
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

    public function testPatchToUpdateUser(): void
    {
        $user = UserFactory::createOne();

        $this->browser()
            ->actingAs($user)
            ->patch('/api/users/'.$user->getId(), [
                'json' => [
                    'username' => 'changed',
                ],
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json'
                ]
            ])
            ->assertStatus(200)
        ;
    }

    public function testTreasureCannotBeStolen(): void
    {
        $user = UserFactory::createOne();
        $anotherUser = UserFactory::createOne();
        $dragonTreasure = DragonTreasureFactory::createOne([
            'owner' => $anotherUser
        ]);

        $this->browser()
            ->actingAs($user)
            ->patch('/api/users/'.$user->getId(), [
                'json' => [
                    'username' => 'changed',
                    'dragonTreasures' => [
                        '/api/treasures/'.$dragonTreasure->getId()
                    ]
                ],
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json'
                ]
            ])
            ->assertStatus(422)
        ;
    }

    public function testUnpublishedTreasuresNotReturned(): void
    {
        $user = UserFactory::createOne();
        DragonTreasureFactory::createOne([
            'isPublished' => false,
            'owner' => $user
        ]);

        $this->browser()
            ->actingAs(UserFactory::createOne())
            ->get('/api/users/'.$user->getId())
            ->assertJsonMatches('length("dragonTreasures")', 0)
        ;
    }
}
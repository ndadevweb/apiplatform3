<?php

namespace App\DataFixtures;

use App\Factory\ApiTokensFactory;
use App\Factory\DragonTreasureFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'email' => 'bernie@dragonmail.com',
            'password' => 'roar',
        ]);

        UserFactory::createMany(10);
        DragonTreasureFactory::createMany(40, function () {
            return [
                'owner' => UserFactory::random(),
                'isPublished' => rand(0, 10) > 3
            ];
        });

        ApiTokensFactory::createMany(30, function() {
            return [
                'ownedBy' => UserFactory::random()
            ];
        });
    }
}

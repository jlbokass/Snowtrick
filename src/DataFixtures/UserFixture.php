<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixture extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(User::class, 15, function (User $user){
            $user->setFirstName($this->faker->name)
                ->setEmail($this->faker->email);
        });

        $manager->flush();
    }
}

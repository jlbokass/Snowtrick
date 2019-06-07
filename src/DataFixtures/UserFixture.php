<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function loadData(ObjectManager $manager)
    {
       $this->createMany(User::class, 2, function(User $user) {

           $user->setEmail($this->faker->email);
           $user->setPassword($this->encoder->encodePassword($user, '123456'));
           $user->setRoles(['ROLE_USER']);
           $user->setUsername($this->faker->userName);
       });

        $manager->flush();
    }
}

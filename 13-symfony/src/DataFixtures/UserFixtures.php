<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('usuario')
            ->setPassword('$argon2id$v=19$m=65536,t=4,p=1$6JiRycZUeQ93a/7IOJzexA$EiUmr0GDBrE8uUvvK5vvPj8BTv4vxBaTeQH5H/xxFbg'); // 123456
        
        $manager->persist($user);

        $manager->flush();
    }
}

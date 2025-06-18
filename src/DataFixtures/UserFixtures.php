<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class UserFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $passwordEncoder)
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($usr =0; $usr < 5; $usr++){
            $user = new User();
            $user->setEmail($faker->email())
                 ->setPassword($this->passwordEncoder->hashPassword($user,'ArethiA1975!'))
                 ->setIsVerified(mt_rand(0,1) === 1 ? true : false)
                 ->setRoles(mt_rand(0,1) === 1 ? ['ROLE_USER']: ['ROLE_USER','ROLE_REDACTOR'])
                 ->setCreatedAt(new \DateTimeImmutable())
                ->setIsVerified(mt_rand(0,1) === 1 ? true : false)
                ->setIsFull(false)
                ->setIsLetter(mt_rand(0,1) === 1 ? true: false);
            $manager->persist($user);
        }

        $manager->flush();
    }
}

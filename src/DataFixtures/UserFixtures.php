<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasherInterface;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface) 
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Admin role
        $admin = new User();
        $admin->setEmail('admin@admin.fr')
              ->setPassword($this->userPasswordHasherInterface->hashPassword($admin, 'admin'))
              ->setRoles(['ROLE_ADMIN'])
              ->setVerified(true); 
        $manager->persist($admin);

        // User role
        $user = new User();
        $user->setEmail('user@user.fr')
             ->setPassword($this->userPasswordHasherInterface->hashPassword($user, 'user'))
             ->setRoles(['ROLE_USER'])
             ->setVerified(true); 
        $manager->persist($user);

        // random users 
        for ($i = 0; $i < 3; $i++) {
            $randomUser = new User();
            $randomUser->setEmail($faker->unique()->email())
                       ->setPassword($this->userPasswordHasherInterface->hashPassword($randomUser, 'password'))
                       ->setRoles(['ROLE_USER'])
                       ->setVerified($faker->boolean(100)); 
            $manager->persist($randomUser);
        }

        $manager->flush();
    }
}

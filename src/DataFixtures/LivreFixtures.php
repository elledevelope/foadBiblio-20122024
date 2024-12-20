<?php

namespace App\DataFixtures;

use App\Entity\Livre;
use App\Entity\User;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LivreFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {       
        $faker = Factory::create('fr_FR');

        $users = $manager->getRepository(User::class)->findAll();
        $categories = $manager->getRepository(Category::class)->findAll();

        if (empty($users) || empty($categories)) {
            throw new \Exception('Please ensure that User and Category entities have records in the database.');
        }

        // Faker data
        for ($i = 0; $i < 20; $i++) {
            $livre = new Livre();
            $livre->setTitle($faker->sentence(3)) 
                  ->setAuteur($faker->name)       
                  ->setUser($faker->randomElement($users)) 
                  ->setCategory($faker->randomElement($categories)); 

            $manager->persist($livre);
        }
      
        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        
        $predefinedCategories = ['Science', 'Technologie', 'Fiction', 'Histoire', 'Biographie', 'Aventure'];
        
        foreach ($predefinedCategories as $predefinedCategory) {
            $category = new Category();
            $category->setNom($predefinedCategory);
            $manager->persist($category);
        }
        $manager->flush();
    }
}

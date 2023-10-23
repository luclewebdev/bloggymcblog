<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = \Faker\Factory::create();

        for($i=0;$i<10;$i++) {


            $article = new Article();
            $article->setName($faker->name);
            $article->setContent($faker->sentence);
            $manager->persist($article);
        }
        $manager->flush();

    }
}

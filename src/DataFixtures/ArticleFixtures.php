<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // On configure dans quelles langues nous voulons nos données
        $faker = Faker\Factory::create('fr_FR');

        // on créé 50 articles
        for ($i = 0; $i < 50; $i++) {
            $article = new Article();
            $article->setTitle(mb_strtolower($faker->sentence()));
            $article->setContent(mb_strtolower($faker->text));
            $manager->persist($article);
            $article->setCategory($this->getReference('categorie_' . rand(0, 4)));
        }
        $manager->flush();
    }

    public function getDependencies()
    {
     return [CategoryFixtures::class];
    }
}

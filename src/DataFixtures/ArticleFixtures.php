<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $article = new Article();
        $article->setCreatedAt(new \DateTime());
        $article->setTitle('Bienvenue sur le blog');
        $article->setSlug('bienvenue-sur-le-blog');
        $article->setContent('<h1>Bienvenue</h1><p>Pour modifier le contenu de cette page, allez dans l\'administration du site, située en haut a gauche de la page une fois connecté.</p>');
        $article->setFeaturedText('<h1>Bienvenue</h1><p>Pour modifier le contenu de cette page, allez dans l\'administration du site, située en haut a gauche de la page une fois connecté.</p>');
        $article->setFeaturedImage($this->getReference('media1'));
        $article->setCreatedAt(new \DateTime());
        $article->setUpdatedAt(new \DateTime());
        $article->setIsFeatured(true);
        $article->setIsDraft(false);
        $article->setAuthor($this->getReference('user1'));
        $article->setFeaturedImage($this->getReference('media1'));
        $manager->persist($article);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return[
            MediaFixtures::class,
            UserFixtures::class
        ];
    }
}

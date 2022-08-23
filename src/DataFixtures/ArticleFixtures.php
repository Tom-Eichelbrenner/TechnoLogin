<?php

namespace App\DataFixtures;

use App\Entity\Article;
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
        $article->setSlug('bienvenue');
        $article->setContent('<h1>Bienvenue sur le blog</h1><p>Pour accéder à l\'espace d\'administration, cliquez <a href="/admin">ici</a>.</p>');
        $article->setIsFeatured(true);
        $article->setFeaturedText('Bienvenue sur le blog');
        $article->setFeaturedImage($this->getReference('media1'));
        $manager->persist($article);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return[
            MediaFixtures::class
        ];
    }
}

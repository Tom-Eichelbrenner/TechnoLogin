<?php

namespace App\DataFixtures;

use App\Entity\Media;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MediaFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $image = new Media();
        $image->setFilename('writer.png');
        $image->setAltText('Writer');
        $image->setName('Writer');
        $image->setAuthor($this->getReference('user1'));
        $manager->persist($image);
        $manager->flush();
        $this->addReference('media1', $image);
    }

    public function getDependencies()
    {
        return [
            ArticleFixtures::class,
            UserFixtures::class
        ];
    }
}

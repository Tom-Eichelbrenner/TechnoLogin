<?php

namespace App\DataFixtures;

use App\Entity\Media;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MediaFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $image = new Media();
        $image->setFilename('writer.png');
        $image->setAltText('Writer');
        $image->setName('Writer');
        $manager->persist($image);
        $manager->flush();
        $this->addReference('media1', $image);
    }
}

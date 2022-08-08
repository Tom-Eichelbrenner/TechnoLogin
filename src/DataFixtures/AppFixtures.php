<?php

namespace App\DataFixtures;

use App\Entity\Option;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\DBAL\Types\TextType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $options[] = new Option('Texte du copyright', 'blog_copyright', 'Tous droits réservés', TextType::class);
        $options[] = new Option('Nombre d\'articles par page', 'blog_articles_per_page', '5', NumberType::class);
        $options[] = new Option('Tout le monde peut s\'inscrire', 'blog_allow_registration', true, BooleanType::class);

        foreach ($options as $option) {
            $manager->persist($option);
        }
        $manager->flush();
    }
}

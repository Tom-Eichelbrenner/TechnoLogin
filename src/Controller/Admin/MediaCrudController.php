<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MediaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Media::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud->setEntityLabelInSingular('Médias')
            ->setEntityLabelInPlural('Médias');
        return $crud;
    }

    public function configureFields(string $pageName): iterable
    {
        $mediasDir = $this->getParameter('medias_directory');
        $uploadsDir = $this->getParameter('uploads_directory');

        yield TextField::new('name', 'Nom')
            ->setRequired(true)
            ->setHelp('Le nom du fichier sans extension.');

        yield TextField::new('altText', 'Texte alternatif')
            ->setHelp('Le texte alternatif est utilisé pour les médias qui ne sont pas des images.');

        $imageField = ImageField::new('filename', 'Média')
            ->setBasePath($uploadsDir)
            ->setUploadDir($mediasDir)
            ->setUploadedFileNamePattern('[slug]-[uuid].[extension]')
            ->setHelp('Le média est une image.');

        if (Crud::PAGE_EDIT === $pageName) {
            $imageField->setRequired(false);
        }

        yield $imageField;
    }
}

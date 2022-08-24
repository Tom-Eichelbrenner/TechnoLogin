<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud->setEntityLabelInSingular('Catégorie')
            ->setEntityLabelInPlural('Catégories');
        return $crud;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            yield TextField::new('name', 'Nom')
                ->setRequired(true)
                ->setHelp('Le nom de la catégorie.'),
            yield SlugField::new('slug', 'Slug')
                ->setTargetFieldName('name')
                ->setHelp('Le slug est un identifiant unique pour la catégorie.'),
            yield TextField::new('description', 'Description')
                ->setHelp('La description de la catégorie.'),
            yield ColorField::new('color', 'Couleur')
                ->setHelp('La couleur de la catégorie.'),
        ];
    }
}

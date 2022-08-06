<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud->setEntityLabelInSingular('Article')
            ->setEntityLabelInPlural('Articles');
        return $crud;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title', 'Titre')
            ->setRequired(true)
            ->setHelp('Le titre de l\'article.');
        yield SlugField::new('slug', 'Slug')
            ->setTargetFieldName('title')
            ->setHelp('Le slug est un identifiant unique pour l\'article.');
        yield TextEditorField::new('content', 'Contenu')
            ->setRequired(true)
            ->setHelp('Le contenu de l\'article.');
        yield TextField::new('featuredText', 'Texte de l\'article en vedette')
            ->setHelp('Le texte de l\'article en vedette sera affiché sur la présentation de l\'article.');
        yield AssociationField::new('categories', 'Catégories')
            ->setHelp('Les catégories sont des mots-clés qui permettent de classer les articles.');
        yield AssociationField::new('featuredImage', 'Image de l\'article en vedette')
            ->setHelp('L\'image de l\'article en vedette sera affichée sur la présentation de l\'article.');

        yield DateTimeField::new('createdAt', 'Créé le')
            ->hideOnForm();
        yield DateTimeField::new('updatedAt', 'Mis à jour le')
            ->hideOnForm();

    }
}

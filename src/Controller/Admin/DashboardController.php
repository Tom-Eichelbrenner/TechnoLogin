<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Media;
use App\Entity\Menu;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ArticleCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('TechnoLogin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Aller sur le site', 'fa fa-undo', 'app_home');

        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::subMenu('Menus', 'fas fa-list')
                ->setSubItems([
                    MenuItem::linkToCrud('Pages', 'fa fa-file', Menu::class),
                    MenuItem::linkToCrud('Articles', 'fa fa-newspaper', Menu::class),
                    MenuItem::linkToCrud('Liens personnalisés', 'fa fa-link', Menu::class),
                    MenuItem::linkToCrud('Catégories', 'fab fa-delicious', Menu::class),
                ]);
        }

        if ($this->isGranted('ROLE_AUTHOR')) {
            yield MenuItem::subMenu('Articles', 'fas fa-newspaper')
                ->setSubItems([
                    MenuItem::linkToCrud('Tous les articles', 'fa fa-newspaper', Article::class),
                    MenuItem::linkToCrud('Ajouter un article', 'fa fa-plus', Article::class)->setAction(Crud::PAGE_NEW),
                    MenuItem::linkToCrud('Catégories', 'fa fa-list', Category::class),
                ]);

            yield MenuItem::subMenu('Médias', 'fas fa-images')
                ->setSubItems([
                    MenuItem::linkToCrud('Tous les médias', 'fa fa-images', Media::class),
                    MenuItem::linkToCrud('Ajouter un média', 'fa fa-plus', Media::class)->setAction(Crud::PAGE_NEW),
                ]);
        }

        if ($this->isGranted('ROLE_ADMIN')){
            yield MenuItem::linkToCrud('Commentaires', 'fa fa-comments', Comment::class);
            yield MenuItem::subMenu('Comptes', 'fas fa-user')
                ->setSubItems([
                    MenuItem::linkToCrud('Tous les comptes', 'fa fa-users', User::class),
                    MenuItem::linkToCrud('Ajouter un compte', 'fa fa-plus', User::class)->setAction(Crud::PAGE_NEW),
                ]);
        }
    }
}

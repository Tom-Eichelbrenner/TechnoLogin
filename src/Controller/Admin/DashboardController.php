<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
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
        yield MenuItem::subMenu('Articles', 'fas fa-newspaper')
            ->setSubItems([
                MenuItem::linkToCrud('Tous les articles', 'fa fa-newspaper', Article::class),
                MenuItem::linkToCrud('Ajouter un article', 'fa fa-plus', Article::class)->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud('Catégories', 'fa fa-list', Category::class),
            ]);
        yield MenuItem::linkToCrud('Commentaires', 'fa fa-comments', Comment::class);
    }
}

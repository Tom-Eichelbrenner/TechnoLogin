<?php

namespace App\Twig;

use App\Entity\Menu;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    const ADMIN_NAMESPACE = 'App\Controller\Admin';
    public function __construct(
        private RouterInterface $router,
        private AdminUrlGenerator $adminUrlGenerator
    )
    {
    }

    public function getFilters()
    {
        return [
            new \Twig\TwigFilter('menuLink', [$this, 'menuLink']),
        ];
    }

    public function menuLink(Menu $menu)
    {
        $article = $menu->getArticle();
        $category = $menu->getCategory();
        $page = $menu->getPage();

        $url = $menu->getLink() ?: '#';
        if ($url !== '#') {
            return $url;
        }
        if ($article) {
            $name = 'article_show';
            $slug = $article->getSlug();
        }
        if ($category) {
            $name = 'category_show';
            $slug = $category->getSlug();
        }
        if ($page) {
            $name = 'page_show';
            $slug = $page->getSlug();
        }
        if(!isset($name)||!isset($slug)){
            return $url;
        }
        return $this->router->generate($name, ['slug' => $slug]);
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('ea_gen_url', [$this, 'getAdminUrl']),
        ];
    }

    public function getAdminUrl(string $controller, ?string $action = null): string
    {
        $adminGenerator = $this->adminUrlGenerator
            ->setController(self::ADMIN_NAMESPACE . '\\' . $controller);

        if ($action) {
            $this->adminUrlGenerator->setAction($action);
        }

        return $adminGenerator->generateUrl();


    }
}
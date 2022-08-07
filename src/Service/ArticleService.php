<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ArticleService
{
    public function __construct(
        private RequestStack $requestStack,
        private ArticleRepository $articleRepository,
        private PaginatorInterface $paginator
    ){
    }

    /**
     * @param Category|null $category
     * @return PaginationInterface
     */
    public function getPaginatedArticles(?Category $category = null): PaginationInterface
    {
        $request = $this->requestStack->getMainRequest();
        $page = $request->query->getInt('page', 1);
        $limit = 2; //todo: get from config
        $articlesQuery = $this->articleRepository->findForPagination($category);

        return $this->paginator->paginate($articlesQuery, $page, $limit);
    }
}
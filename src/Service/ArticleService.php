<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ArticleService
{
    public function __construct(
        private RequestStack           $requestStack,
        private ArticleRepository      $articleRepository,
        private PaginatorInterface     $paginator,
        private OptionService          $optionService,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    /**
     * @param Category|null $category
     * @return PaginationInterface
     */
    public function getPaginatedArticles(?Category $category = null): PaginationInterface
    {
        $request = $this->requestStack->getMainRequest();
        $page = $request->query->getInt('page', 1);
        $limit = $this->optionService->getValue('blog_articles_per_page');
        $articlesQuery = $this->articleRepository->findForPagination($category);

        return $this->paginator->paginate($articlesQuery, $page, $limit);
    }

    public function getFeaturedArticle(): ?Article
    {
        return $this->articleRepository->findOneBy(['is_featured' => true]);
    }

    public function toggleLike(Article $article, User $user): string
    {
        if ($article->getLikes()->contains($user)) {
            $article->getLikes()->removeElement($user);
            $message = 'DISLIKE';
        } else {
            $article->getLikes()->add($user);
            $message = 'LIKE';
        }
        $this->entityManager->persist($article);
        $this->entityManager->flush();
        return $message;
    }

    public function getLikeCount(Article $article): int
    {
        return $article->getLikes()->count();
    }

}
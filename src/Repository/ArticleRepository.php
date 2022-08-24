<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findForPagination(?Category $category = null): \Doctrine\ORM\Query
    {
        $query = $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC');

        if ($category) {
            $query->leftJoin('a.categories', 'c')
                ->where($query->expr()->eq('c.id', ':categoryId'))
                ->setParameter('categoryId', $category->getId());
        }
        $query->andWhere($query->expr()->eq('a.is_featured', ':is_featured'))
            ->setParameter('is_featured', false)
            ->andWhere('a.is_draft = false');

        return $query->getQuery();
    }
}

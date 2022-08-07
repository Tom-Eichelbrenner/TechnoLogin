<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findForPagination(?Article $article = null): \Doctrine\ORM\Query
    {
        $query = $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'DESC');

        if ($article) {
            $query->leftJoin('c.article', 'a')
                ->where($query->expr()->eq('a.id', ':articleId'))
                ->setParameter('articleId', $article->getId());
        }

        return $query->getQuery();
    }
}

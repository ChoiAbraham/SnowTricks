<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Comment;
use App\Domain\Repository\Interfaces\CommentRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CommentRepository extends ServiceEntityRepository implements CommentRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @return Comment[]
     */
    public function findComments($slug, $maxresult, $offset): array
    {
        return $this->createQueryBuilder('comment')
            ->andWhere('comment.trick = :slug')
            ->setParameter('slug', $slug)
            ->orderBy('comment.id', 'ASC')
            ->setMaxResults($maxresult)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }
}

<?php

namespace App\Domain\Repository;

use App\Domain\Entity\TrickVideo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TrickVideo|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrickVideo|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrickVideo[]    findAll()
 * @method TrickVideo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickVideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrickVideo::class);
    }

    // /**
    //  * @return TrickVideo[] Returns an array of TrickVideo objects
    //  */
    //You can even put OR statements inside the string, like a.publishedAt IS NULL OR a.publishedAt > NOW()
    //'t' is the table alias for TrickVideo just like in sql 'AS t'
    //always call getQuery(), then you'll either call getResult() to return many rows of articles
    //OR getOneOrNullResult() to return a single Article object.
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    //Query Logic Re-use & Shortcuts
    //Step 1 : Isolate the query logic to share,
    //ex : private function addIsPublishedQueryBuilder(QueryBuilder $qb) -
    //QueryBuilder is from Doctrine\ORM
    //just return $qb->andWhere('a.publishedAt IS NOT NULL');
    //One important note is that you need to consistently use the same alias, like a, across all of your methods.

    /*
    private function addIsPublishedQueryBuilder(QueryBuilder $qb)
    {
        return $qb->andWhere('a.publishedAt IS NOT NULL');
    }

    public function findAllPublishedOrderedByNewest()
    {
        $qb = $this->createQueryBuilder('a');

        return $this->addIsPublishedQueryBuilder($qb)
            ->orderBy('a.publishedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
    */

    //Fancier Re-Use
    //the argument QueryBuilder is this time optional
    //When this method is called, if the query builder is passed, we'll just return it.
    //Otherwise we will return a new one with $this->createQueryBuilder('a')
    //Real beautiful thing is we can pass nothing:
    //It will create the QueryBuilder for us.
    /*
    use Doctrine\ORM\QueryBuilder;

    private function getOrCreateQueryBuilder(QueryBuilder $qb = null)
    {
        return $qb ?: $this->createQueryBuilder('a');
    }

    private function addIsPublishedQueryBuilder(QueryBuilder $qb = null)
    {
        return $this->getOrCreateQueryBuilder($qb)
            ->andWhere('a.publishedAt IS NOT NULL');
    }

    public function findAllPublishedOrderedByNewest()
    {
        return $this->addIsPublishedQueryBuilder()
            ->orderBy('a.publishedAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    //Query ShortCut


/*
public function findOneBySomeField($value): ?TrickVideo
{
    return $this->createQueryBuilder('t')
        ->andWhere('t.exampleField = :val')
        ->setParameter('val', $value)
        ->getQuery()
        ->getOneOrNullResult()
    ;
}
*/
}

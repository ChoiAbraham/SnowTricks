<?php


namespace App\Domain\Repository;

use App\Domain\Entity\Trick;
use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TrickRepository extends ServiceEntityRepository implements TrickRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    /**
     * @return Trick[]
     */
    public function findLatestWithFirstImageActive($maxresult, $offset): array
    {
        return $this->createQueryBuilder('trick')
            ->orderBy('trick.id', 'DESC')
            ->leftJoin('trick.trickImages', 'trick_images')
            ->andWhere('trick_images.firstImage = true')
            ->setMaxResults($maxresult)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }
}
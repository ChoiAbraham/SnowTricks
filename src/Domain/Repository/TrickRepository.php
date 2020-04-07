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
    public function findLatest(): array
    {
        return $this->createQueryBuilder('t')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }
}
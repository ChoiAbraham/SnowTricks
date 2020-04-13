<?php


namespace App\Domain\Repository;

use App\Domain\Entity\User;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByCredentials(string $value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.name = :value OR a.email = :value')
            ->setParameter('value', $value)
            ->getQuery()
            ->getResult()
            ;
    }


    /*
    /**
     * @param $value
     * @return User[] Returns an array of User objects

    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
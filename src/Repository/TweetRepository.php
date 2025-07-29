<?php

namespace App\Repository;

use App\Entity\Tweet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tweet>
 */
class TweetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tweet::class);
    }

        public function findByIsSignaled(bool $isSignaled): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.isSignaled = :val')
            ->setParameter('val', $isSignaled)
            ->orderBy('t.creationTime', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function countByIsSignaled(bool $isSignaled): int
    {
        return $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.isSignaled = :val')
            ->setParameter('val', $isSignaled)
            ->getQuery()
            ->getSingleScalarResult();
    }

    //    /**
    //     * @return Tweet[] Returns an array of Tweet objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Tweet
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

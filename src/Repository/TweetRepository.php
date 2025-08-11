<?php

namespace App\Repository;

use App\Entity\Tweet;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

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

     public function findPaginatedByUser(User $user, int $limit, int $offset): array
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->andWhere('t.idUser = :user') // Utilisez 'idUser' comme défini dans votre entité Tweet
            ->setParameter('user', $user)
            ->orderBy('t.creationTime', 'DESC') // Triez par temps de création décroissant
            ->setFirstResult($offset) // Définissez l'offset
            ->setMaxResults($limit); // Définissez la limite de résultats par page

        $paginator = new Paginator($queryBuilder->getQuery());

        return [
            'tweets' => $paginator->getIterator(), // Récupère les tweets paginés pour la page actuelle
            'totalCountTweets' => $paginator->count(), // Récupère le nombre total de tweets pour cet utilisateur (sans la limite/offset)
        ];
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

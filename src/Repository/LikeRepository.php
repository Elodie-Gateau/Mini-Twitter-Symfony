<?php

namespace App\Repository;

use App\Entity\Like;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Like>
 */
class LikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Like::class);
    }

       public function findPaginatedByUser(User $user, int $limit, int $offset): array
    {
        $queryBuilder = $this->createQueryBuilder('l')
            ->join('l.tweet', 't') // Joint explicitement l'entité Like avec son entité Tweet associée, aliasée en 't'
            ->andWhere('l.user = :user') // Utilisez 'idUser' comme défini dans votre entité Tweet
            ->setParameter('user', $user)
            ->orderBy('t.creationTime', 'DESC') // Triez par temps de création décroissant
            ->setFirstResult($offset) // Définissez l'offset
            ->setMaxResults($limit); // Définissez la limite de résultats par page

        $paginator = new Paginator($queryBuilder->getQuery());

        return [
            'likes' => $paginator->getIterator(), // Récupère les likes paginés pour la page actuelle
            'totalCountLikes' => $paginator->count(), // Récupère le nombre total de likes pour cet utilisateur (sans la limite/offset)
        ];
    }
//    /**
//     * @return Like[] Returns an array of Like objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Like
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

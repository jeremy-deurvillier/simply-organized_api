<?php

namespace App\Repository;

use App\Entity\Quicknote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quicknote>
 *
 * @method Quicknote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quicknote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quicknote[]    findAll()
 * @method Quicknote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuicknoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quicknote::class);
    }

//    /**
//     * @return Quicknote[] Returns an array of Quicknote objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Quicknote
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

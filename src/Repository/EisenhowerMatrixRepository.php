<?php

namespace App\Repository;

use App\Entity\EisenhowerMatrix;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EisenhowerMatrix>
 *
 * @method EisenhowerMatrix|null find($id, $lockMode = null, $lockVersion = null)
 * @method EisenhowerMatrix|null findOneBy(array $criteria, array $orderBy = null)
 * @method EisenhowerMatrix[]    findAll()
 * @method EisenhowerMatrix[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EisenhowerMatrixRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EisenhowerMatrix::class);
    }

//    /**
//     * @return EisenhowerMatrix[] Returns an array of EisenhowerMatrix objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EisenhowerMatrix
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

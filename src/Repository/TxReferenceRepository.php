<?php

namespace App\Repository;

use App\Entity\TxReference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TxReference|null find($id, $lockMode = null, $lockVersion = null)
 * @method TxReference|null findOneBy(array $criteria, array $orderBy = null)
 * @method TxReference[]    findAll()
 * @method TxReference[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TxReferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TxReference::class);
    }

    // /**
    //  * @return TxReference[] Returns an array of TxReference objects
    //  */
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

    /*
    public function findOneBySomeField($value): ?TxReference
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

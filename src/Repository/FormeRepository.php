<?php

namespace App\Repository;

use App\Entity\Forme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Forme|null find($id, $lockMode = null, $lockVersion = null)
 * @method Forme|null findOneBy(array $criteria, array $orderBy = null)
 * @method Forme[]    findAll()
 * @method Forme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Forme::class);
    }

    // /**
    //  * @return Forme[] Returns an array of Forme objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Forme
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

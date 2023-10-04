<?php

namespace App\Repository;

use App\Entity\Superficie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Superficie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Superficie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Superficie[]    findAll()
 * @method Superficie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuperficieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Superficie::class);
    }


    
    // public function findplanbysuperficie($id)    
    // { 
        
    //     return $this->createQueryBuilder('s')
    //     ->andWhere ('s.plans = :id')
    //     ->setParameter('id',$id)
    //     ->getQuery()
    //     ->getResult()
    //     ;
    
    // }


    // /**
    //  * @return Superficie[] Returns an array of Superficie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Superficie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

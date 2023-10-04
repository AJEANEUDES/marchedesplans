<?php

namespace App\Repository;

use App\Entity\Consulter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Consulter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Consulter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Consulter[]    findAll()
 * @method Consulter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsulterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Consulter::class);
    }

    // /**
    //  * @return Consulter[] Returns an array of Consulter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Consulter
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    
    public function consulter()    
    { 
        
        return $this->createQueryBuilder('c')
        ->groupBy('c.plans')
        ->getQuery()->getResult()
        
        ;
    
    }

}

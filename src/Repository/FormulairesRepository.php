<?php

namespace App\Repository;

use App\Entity\Formulaires;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Formulaires|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formulaires|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formulaires[]    findAll()
 * @method Formulaires[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormulairesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formulaires::class);
    }

    // /**
    //  * @return Formulaires[] Returns an array of Formulaires objects
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
    public function findOneBySomeField($value): ?Formulaires
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

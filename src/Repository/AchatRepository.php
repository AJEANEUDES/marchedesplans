<?php

namespace App\Repository;

use App\Entity\Achat;
use App\Entity\Plans;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;


/**
 * @method Achat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Achat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Achat[]    findAll()
 * @method Achat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AchatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator )
    {
        parent::__construct($registry, Achat::class);
        $this-> paginator= $paginator;


    }

   // /**
   //  * @return Achat[] Returns an array of Achat objects
    // */
    
   /*  public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a','p','u')
            ->select('a','p','u')
            ->from(Achat,'a',Plans,'p',Users,'u')
            ->where('a.p.u = u.id')           
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
 */


public function finduser(int $user): array
{
    $entityManager = $this->getEntityManager();

    $query = $entityManager->createQuery(
        'SELECT *
        FROM App\Entity\Achat a
        FROM App\Entity\Plans p
        FROM App\Entity\Users u
        WHERE a.plan_id = p.id
        AND   p.user_id  = u.id'
       
    )->setParameter('p.user_id', $user);
    // returns an array of Product objects
    return $query->getResult();
}

// SELECT * FROM achat
// JOIN   plans ON achat.plan_id = plans.id  
// WHERE   plans.user_id  = 3

    /**
     * @return Achat[]
     */
    public function achat($payement): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a,p,u
            FROM App\Entity\Achat a
            FROM App\Entity\Plans p
            FROM App\Entity\Users u

            WHERE a.p.user_id = :user_id
            ORDER BY p.user_id ASC'
        )->setParameter('user_id', $payement);

        // returns an array of Product objects
        return $query->getResult();
    }

  

    
    public function achatlist($id)    
    { 
        
        return $this->createQueryBuilder('u')
        ->where('u.users= :id')
        ->andWhere ("u.etat = 'en cours'")
        // ->andWhere ("u.payement = 'paygate'")
        ->setParameter('id',$id,)
        ->getQuery()
        ->getArrayResult()
        
        ;
    
    }

  

    public function plans_les_plus_achater()    
    { 
        
        return $this->createQueryBuilder('achat')
            ->groupBy('achat.plan')
            ->getQuery()->getResult()
        
        ;
    
    }

    // public function paginer(): PaginationInterface
    // {

    //       return $this ->paginator->paginate(
    //         getInt('page',4)
    //       );

    // }


    
    /*
    public function findOneBySomeField($value): ?Achat
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

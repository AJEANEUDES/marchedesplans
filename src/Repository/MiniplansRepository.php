<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Miniplans;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Miniplans|null find($id, $lockMode = null, $lockVersion = null)
 * @method Miniplans|null findOneBy(array $criteria, array $orderBy = null)
 * @method Miniplans[]    findAll()
 * @method Miniplans[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MiniplansRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Miniplans::class);
        $this-> paginator= $paginator;

    }
   
    


    // /**
    //  * @return Miniplans[] Returns an array of Miniplans objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Miniplans
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function findVisibleQuery1($id)    
    { 
        
        return $this->createQueryBuilder('u')
        ->where('u.id = :id')
        ->setParameter('id',$id)
        ->getQuery()
        ->getResult()
        ;
    
    }



    /**
     * recupere les produits
     * @return PaginationInterface
     */

    public function findsearchplan(SearchData $search): PaginationInterface
    {

        $query = $this
        
        ->createQueryBuilder('p')
        ->select('t','p')
         ->join('p.type','t'); 

         if(!empty($search ->q)){
             $query = $query
             ->andWhere('p.titre LIKE :q')
             ->setParameter('q',"%{$search->q}%");
         }



         if(!empty($search ->min)){
            $query = $query
            ->andWhere('p.prix >= :min')
            ->setParameter('min',$search->min);
        }

        if(!empty($search ->max)){
            $query = $query
            ->andWhere('p.prix <= :max')
            ->setParameter('max',$search->max);
        }


        // if(!empty($search ->superficie)){
        //     $query = $query
        //     ->andWhere('p.superficie = :superficie')
        //     ->setParameter('superficie',$search->superficie);
        // }


        if(!empty($search ->nbre_piece)){
            $query = $query
            ->andWhere('p.nbre_piece = :nbre_piece')
            ->setParameter('nbre_piece',$search->nbre_piece);
        }



        if(!empty($search ->types)){
            $query = $query
            ->andWhere('t.id IN (:types)')
            ->setParameter('types',$search->types);
        }

        
        if(!empty($search ->superficies)){
            $query = $query
            ->andWhere('t.id IN (:superficies)')
            ->setParameter('superficies',$search->superficies);
        }


        
        if(!empty($search ->formes)){
            $query = $query
            ->andWhere('t.id IN (:formes)')
            ->setParameter('formes',$search->formes);
        }



       /*  return $query ->getQuery()->getResult();
 */

          $query = $query->getQuery();

          return $this ->paginator->paginate(
              $query,
              $search->page,12
          );


    }
   




   
   

}

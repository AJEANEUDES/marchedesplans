<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Miniplans;
use App\Entity\Plans;
use App\Entity\Type;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\TypeRegistry;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @method Plans|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plans|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plans[]    findAll()
 * @method Plans[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlansRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator )
    {
        parent::__construct($registry, Plans::class);
        $this-> paginator= $paginator;
    }



     /**
     * Undocumented function
     *
     * @return QueryBuilder
     */
    public function findVisibleQuery() :QueryBuilder
    {  return $this->createQueryBuilder('p')
        // ->where('p.sold=false')
        ;
    
    }


     /**
      *  @return array
      */

    public function findLatest() :array
    {
        return $this->findVisibleQuery()
        ->setMaxResults(4)
        ->getQuery()
        ->getResult();
    }

    


    public function findVisibleQuery1($id)    
    { 
        
        return $this->createQueryBuilder('p')
        ->where('p.id = :id')
        // ->andWhere ("u.roles = 'R2'")
        ->setParameter('id',$id)
        ->getQuery()
        ->getResult()
        ;
    
    }



    public function getminiplan($plan)
    {
        $id=$this->getId();
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->select('mp')
            ->from('Entity:Miniplans','mp')
            ->where('mp.plan =: $id')
            ->getQuery()->getResult()
        ;

    }


    public function searchplan($critere)
    {
        return $this->createQueryBuilder('p')
            /* ->Join('p.type', 'type')
            ->addSelect('type')
            ->where('type.libelle =: typelib')
            ->setParameter('typelib',$critere->getType()->getLibelle()) */

            ->where('p.type =:t')
            ->setParameter('t',$critere->getType())
           
            ->andWhere('p.nbre_piece =:piece')
            ->setParameter('piece',$critere->getNbrePiece())
            
            ->andWhere('p.nbre_etage =:etage')
            ->setParameter('etage',$critere->getNbreEtage())
            
            ->andWhere('p.superficie =:s')
            ->setParameter('s',$critere->getSuperficie())
            
            ->andWhere('p.prix >:min')
            ->setParameter('min',$critere->getPrix())
            
            ->andWhere('p.prix < :max')
            ->setParameter('max',$critere->getPrix())
            
            ->getQuery()->getResult()
        ;
    }



    public function findplanbytype($id)    
    { 
        
        return $this->createQueryBuilder('p')
        ->andWhere ('p.type = :id')
        ->setParameter('id',$id)
        ->getQuery()
        ->getResult()
        ;
    
    }


    public function findplanbysuperficie($id)    
    { 
        
        return $this->createQueryBuilder('p')
        ->andWhere ('p.superficie = :id')
        ->setParameter('id',$id)
        ->getQuery()
        ->getResult()
        ;
    
    }

    /**
     * recupere les plans
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
            ->join('p.miniplans','mp')
            ->andWhere('mp.prix >= :min')
            ->setParameter('min',$search->min);
        }

        if(!empty($search ->max)){
            $query = $query
            ->join('p.miniplans','m')
            ->andWhere('m.prix <= :max')
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

        //récupère les pages

          $query = $query->getQuery();

          return $this ->paginator->paginate(
              $query,
              $search->page,12
          );


    }
   


     /* return $this->createQueryBuilder('genus')
            ->andWhere('genus.isPublished = :isPublished')
            ->setParameter('isPublished', true)
            ->leftJoin('genus.notes', 'genus_note')
            ->getQuery()
            ->execute(); */
   
    
    

    // /**
    //  * @return Plans[] Returns an array of Plans objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Plans
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

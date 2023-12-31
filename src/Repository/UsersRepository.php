<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Users) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function findVisibleQuery() 
    {  return $this->createQueryBuilder('u')
        ->andWhere ('u.roles = :val')
        ->setParameter('val', "{'ROLE_ADMIN'}")
        ->getQuery()
        ->getArrayResult()
        ;
    
    }

    public function findAdminUser($id)
    {
    //    $id=$this->getUser();
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT u
             FROM APP\Entity\USERS u
             WHERE u.roles = nnn
             AND u.id !=$id'
        );
        return $query->getResult();
    }


    public function findVisibleQuery1($id)    
    { 
        
        return $this->createQueryBuilder('u')
        ->where('u.id != :id')
        ->andWhere ("u.roles = 'R2'")
        ->setParameter('id',$id)
        ->getQuery()
        ->getResult()
        ;
    
    }

   
   

    public function findUsers()
    {
        return $this->createQueryBuilder('u')
            ->where('u.exampleField = :val')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Users[] Returns an array of Users objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Users
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

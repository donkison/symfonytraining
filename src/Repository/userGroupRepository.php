<?php

namespace App\Repository;

use App\Entity\userGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method userGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method userGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method userGroup[]    findAll()
 * @method userGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class userGroupRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, userGroup::class);
    }

    // /**
    //  * @return Groups[] Returns an array of Groups objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Groups
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

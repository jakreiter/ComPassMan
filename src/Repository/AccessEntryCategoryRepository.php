<?php

namespace App\Repository;

use App\Entity\AccessEntryCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AccessEntryCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessEntryCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessEntryCategory[]    findAll()
 * @method AccessEntryCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessEntryCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessEntryCategory::class);
    }

    // /**
    //  * @return AccessEntryCategory[] Returns an array of AccessEntryCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AccessEntryCategory
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

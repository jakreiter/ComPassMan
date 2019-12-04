<?php

namespace App\Repository;

use App\Entity\AccessEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AccessEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessEntry[]    findAll()
 * @method AccessEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessEntry::class);
    }

    // /**
    //  * @return AccessEntry[] Returns an array of AccessEntry objects
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
    public function findOneBySomeField($value): ?AccessEntry
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

<?php

namespace App\Repository;

use App\Entity\AboutMePage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AboutMePage|null find($id, $lockMode = null, $lockVersion = null)
 * @method AboutMePage|null findOneBy(array $criteria, array $orderBy = null)
 * @method AboutMePage[]    findAll()
 * @method AboutMePage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AboutMePageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AboutMePage::class);
    }

    // /**
    //  * @return AboutMePage[] Returns an array of AboutMePage objects
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
    public function findOneBySomeField($value): ?AboutMePage
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

<?php

namespace App\Repository;

use App\Entity\PortofolioPage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PortofolioPage|null find($id, $lockMode = null, $lockVersion = null)
 * @method PortofolioPage|null findOneBy(array $criteria, array $orderBy = null)
 * @method PortofolioPage[]    findAll()
 * @method PortofolioPage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PortofolioPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PortofolioPage::class);
    }

    // /**
    //  * @return PortofolioPage[] Returns an array of PortofolioPage objects
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
    public function findOneBySomeField($value): ?PortofolioPage
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

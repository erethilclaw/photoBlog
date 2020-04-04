<?php

namespace App\Repository;

use App\Entity\ContactPage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ContactPage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactPage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactPage[]    findAll()
 * @method ContactPage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactPage::class);
    }

    // /**
    //  * @return ContactPage[] Returns an array of ContactPage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContactPage
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

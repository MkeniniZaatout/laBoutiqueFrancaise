<?php

namespace App\Repository;

use App\Entity\LivraisonDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LivraisonDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method LivraisonDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method LivraisonDetails[]    findAll()
 * @method LivraisonDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivraisonDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LivraisonDetails::class);
    }

    // /**
    //  * @return LivraisonDetails[] Returns an array of LivraisonDetails objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LivraisonDetails
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

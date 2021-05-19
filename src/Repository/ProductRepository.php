<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Classe\Search;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
    * @param Search $search
    */
    public function findWithSearch(Search $search) {

        $query = $this->createQueryBuilder('product')
        ->select('category','product')
        ->join('product.category','category');
        /*
        ->andWhere('product.name = :name ')
        ->andWhere('product.color = :color');
        */
        if(!empty($search->categories)) {
            $query = $query->andWhere('category.id IN (:categories)')->setParameter('categories', $search->categories);
        }

        if(!empty($search->name)) {
            $query = $query->andWhere('product.name LIKE :name')->setParameter('name', "%{$search->name}%");
        }

        if(!empty($search->color)) {
            $query = $query->andWhere('product.color = :color')->setParameter('color', $search->color);
        }

        if(!empty($search->stars)) {
            $query = $query->andWhere('product.stars = :stars')->setParameter('stars', $search->stars);
        }
        
        $result = $query
        ->getQuery()
        ->getResult();

        return $result;
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
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
    public function findOneBySomeField($value): ?Product
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

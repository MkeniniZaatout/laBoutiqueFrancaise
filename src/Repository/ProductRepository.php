<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Classe\Search;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
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

        if(!empty($search->max)) {
            $search->max = ($search->max * 100);
            $query = $query->andWhere('product.price <= :max')->setParameter('max', $search->max);
        }
        
        if(!empty($search->min)){
            $search->max = ($search->min * 100);
            $query = $query->andWhere('product.price >= :min')->setParameter('min', $search->min);
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

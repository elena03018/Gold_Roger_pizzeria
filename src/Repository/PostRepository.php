<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function getProductsWithCategory($category)
    {
        $query = $this->_em->createQuery('SELECT p, c FROM App\Entity\Post p JOIN p.category c WHERE c.name = :category AND p.isPublished = true');
        $query->setParameter('category', $category);
        return $query->getResult();
    }
    
    public function getProductsWithCategoryId($id)
    {
        $query = $this->_em->createQuery('SELECT p, c FROM App\Entity\Post p JOIN p.category c WHERE c.id = :category AND p.isPublished = true');
        $query->setParameter('category', $id);
        return $query->getResult();
    }

//    /**
//     * @return Post[] Returns an array of Post objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

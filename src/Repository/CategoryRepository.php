<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    // /**
    //  * @return Category[] Returns an array of Category objects
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
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param null|string $term
     *
     * @return Category[]
     */
    public function findAllWithSearch(?string $term)
    {
        $qb = $this->createQueryBuilder('c')
            ->innerJoin('c.articles', 'a')
            ->innerJoin('c.author', 'user')
            ->addSelect('a')
            ->addSelect('user');

        if ($term) {
            $qb->andWhere('
            c.content LIKE :term OR c.author LIKE :term 
            OR a.title LIKE :term OR a.content LIKE :term
            OR user.firstName LIKE:term 
            ')
                ->setParameter('term', '%'.$term.'%')
            ;
        }

        return $qb
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllPublishedOrderedByNewest()
    {
        return $this->addIsPublishedQueryBuilder()
            ->orderBy('c.publishedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    private function addIsPublishedQueryBuilder(QueryBuilder $qb = null)
    {
        return $this->getOrCreateQueryBuider($qb)
            ->andWhere('c.publishedAt IS NOT NULL');
    }

    private function addIsNotPublishedQueryBuilder(QueryBuilder $qb = null)
    {
        return $this->getOrCreateQueryBuider($qb)
            ->andWhere('a.publishedAt IS NULL');
    }

    private function getOrCreateQueryBuider(QueryBuilder $qb = null)
    {
        return $qb ? : $this->createQueryBuilder('c');
    }
}

<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */

    /**
     * @param null|string $term
     *
     * @return Article[]
     */
    public function findAllWithSearch(?string $term)
    {
        $qb = $this->createQueryBuilder('a')
            ->innerJoin('a.category', 'c')
            ->addSelect('c')
        ;

        if ($term) {
            $qb->andWhere('
            a.content LIKE :term OR a.author LIKE :term 
            OR c.title LIKE :term OR c.content LIKE :term
            OR user.firstName LIKE :term 
            ')
                ->setParameter('term', '%'.$term.'%')
            ;
        }

        return $qb
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllPublishedOrderedByNewest()
    {
        return $this->addIsPublishedQueryBuilder()
            ->orderBy('a.publishedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllNonPublishedOrderedByNewest()
    {
        return $this->addIsNotPublishedQueryBuilder()
            ->orderBy('a.publishedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public static function createNonDeletedCriteria(): Criteria
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('isDeleted', false))
            ->orderBy(['createdAt' => 'DESC'])
            ;
    }

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    private function addIsPublishedQueryBuilder(QueryBuilder $qb = null)
    {
        return $this->getOrCreateQueryBuider($qb)
            ->andWhere('a.publishedAt IS NOT NULL');
    }

    private function addIsNotPublishedQueryBuilder(QueryBuilder $qb = null)
    {
        return $this->getOrCreateQueryBuider($qb)
            ->andWhere('a.publishedAt IS NULL');
    }

    private function getOrCreateQueryBuider(QueryBuilder $qb = null)
    {
        return $qb ? : $this->createQueryBuilder('a');
    }

}

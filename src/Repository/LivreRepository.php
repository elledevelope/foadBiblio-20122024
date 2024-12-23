<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    //    /**
    //     * @return Livre[] Returns an array of Livre objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Livre
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    // search
    public function searchBy($champ, $value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere("l.$champ LIKE :val")
            ->setParameter('val', "%$value%")
            ->getQuery()
            ->getResult()
        ;
    }

    // category filter 
    public function findByCategory(?int $categoryId): array
    {
        $qb = $this->createQueryBuilder('l');

        if ($categoryId) {
            $qb->andWhere('l.category = :category')
                ->setParameter('category', $categoryId);
        }

        $qb->orderBy('l.title', 'ASC');

        return $qb->getQuery()->getResult();
    }
}

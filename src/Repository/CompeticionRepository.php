<?php

namespace App\Repository;

use App\Entity\Competicion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Competicion>
 */
class CompeticionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Competicion::class);
    }
    public function findByFilters(?string $search, ?string $type): array
    {
        $qb = $this->createQueryBuilder('c');

        if ($search) {
            // Usamos UPPER para que busque igual "champions" que "CHAMPIONS"
            $qb->andWhere('UPPER(c.name) LIKE UPPER(:search)')
                ->setParameter('search', '%' . $search . '%');
        }

        if ($type) {
            $qb->andWhere('c.type = :type')
                ->setParameter('type', $type);
        }

        return $qb->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function findTopRated(int $limit = 5): array
    {
        return $this->createQueryBuilder('c')
            ->select('c as competicion, AVG(r.stars) as media')
            ->join('c.reviews', 'r')
            ->groupBy('c.id')
            ->orderBy('media', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Competicion[] Returns an array of Competicion objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Competicion
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

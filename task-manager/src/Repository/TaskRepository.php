<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findByStatus($status, $page, $limit)
    {
        $queryBuilder = $this->createQueryBuilder('t');

        if ($status) {
            $queryBuilder->andWhere('t.status = :status')
                         ->setParameter('status', $status);
        }

        $queryBuilder->orderBy('t.status', 'ASC')
                     ->setFirstResult(($page - 1) * $limit)
                     ->setMaxResults($limit);

        return $queryBuilder->getQuery()->getResult();
    }

    public function searchByTitleOrDescription($query)
    {
        return $this->createQueryBuilder('t')
                    ->where('t.title LIKE :query OR t.description LIKE :query')
                    ->setParameter('query', '%' . $query . '%')
                    ->getQuery()
                    ->getResult();
    }

    //    /**
    //     * @return Task[] Returns an array of Task objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Task
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

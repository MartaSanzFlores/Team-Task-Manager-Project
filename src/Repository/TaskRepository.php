<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\Project;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function getTaskCompletionPercentage(Project $project): int
    {
        $qb = $this->createQueryBuilder('t')
            ->select('COUNT(t.id) as totalTasks, SUM(CASE WHEN t.progressState = :done THEN 1 ELSE 0 END) as completedTasks')
            ->where('t.project = :project')
            ->setParameter('done', 'done')
            ->setParameter('project', $project)
            ->getQuery()
            ->getSingleResult();

        if ($qb['totalTasks'] == 0) {
            return 0;
        }

        return round(($qb['completedTasks'] / $qb['totalTasks']) * 100);
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

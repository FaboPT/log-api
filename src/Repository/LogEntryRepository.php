<?php

namespace App\Repository;

use App\Entity\LogEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LogEntry>
 */
class LogEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LogEntry::class);
    }

    public function countByCriteria(array $criteria): int
    {
        $qb = $this->createQueryBuilder('l');

        foreach ($criteria as $key => $value) {
            if ('timestamp' === $key) {
                foreach ($value as $operator => $date) {
                    $qb->andWhere("l.timestamp $operator :$operator")
                        ->setParameter($operator, $date);
                }
            } else {
                $qb->andWhere("l.$key = :$key")
                    ->setParameter($key, $value);
            }
        }

        return $qb->select('COUNT(l.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}

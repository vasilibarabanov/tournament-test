<?php

namespace App\Repository;

use App\Entity\Result;
use App\Model\Event\Tournament\Action\GroupStage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Result>
 */
class ResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Result::class);
    }

    /**
     * @return Result[] Returns an array of Result objects
     */
    public function findByStage(int $tournamentId, string $stage): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.stage = :stage')
            ->setParameter('stage', $stage)
            ->andWhere('r.tournamentId = :val')
            ->setParameter('val', $tournamentId)
            ->orderBy('r.stage', 'DESC')
            ->orderBy('r.matchNumber', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findPlayoffStages(int $tournamentId): array
    {
        return $this->createQueryBuilder('r')
            ->select('DISTINCT r.stage')
            ->andWhere('r.stage != :stage')
            ->andWhere('r.tournamentId = :tournamentId')
            ->setParameter('tournamentId', $tournamentId)
            ->setParameter('stage', GroupStage::STAGE)
            ->orderBy('r.stage', 'DESC')
            ->getQuery()
            ->getSingleColumnResult();
    }
}

<?php

namespace App\Repository;

use App\Entity\Result;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Team>
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function findBestTeams(int $tournamentId, array $stage): array
    {
        return $this->createQueryBuilder('team')
            ->select('team.name, count(result.winnerId) as wins')
            ->andWhere('result.tournamentId = :tournamentId')
            ->orWhere('result.tournamentId IS NULL')
            ->setParameter('tournamentId', $tournamentId)
            ->andWhere('result.stage IN (:stage)')
            ->setParameter('stage', $stage)
            ->leftJoin(Result::class, 'result', Join::WITH, 'team.id = result.winnerId')
            ->orderBy('wins', 'DESC')
            ->groupBy('team.id')
            ->getQuery()
            ->getSingleColumnResult();
    }

    public function findPlayoffTeams(int $tournamentId, array $stage): array
    {
        return $this->createQueryBuilder('team')
            ->select('team.name')
            ->andWhere('result.tournamentId = :tournamentId')
            ->setParameter('tournamentId', $tournamentId)
            ->andWhere('result.stage IN (:stage)')
            ->setParameter('stage', $stage)
            ->leftJoin(
                Result::class,
                'result',
                Join::WITH,
                'team.id = result.firstTeamId OR team.id = result.secondTeamId'
            )
            ->groupBy('team.id')
            ->getQuery()
            ->getSingleColumnResult();
    }
}

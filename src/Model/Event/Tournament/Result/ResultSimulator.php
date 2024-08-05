<?php

declare(strict_types=1);

namespace App\Model\Event\Tournament\Result;

use App\Entity\Result;
use App\Entity\Team;

class ResultSimulator implements ResultSimulatorInterface
{
    public function execute(Team $firstTeam, Team $secondTime, string $stage, int $number = 0): Result
    {
        $result = new Result();
        $result->setFirstTeam($firstTeam->getName());
        $result->setSecondTeam($secondTime->getName());
        $result->setFirstTeamId($firstTeam->getId());
        $result->setSecondTeamId($secondTime->getId());
        $score = rand(0, 1);
        /**
         * тут так же изначально были сомнения, в задаче ничего не сказано про возможность ничейного исхода и,
         * более того, не ясно как быть в случае равенства очков при определении плэй-офф команд,
         * полагаю переигровки устраивать было бы слишком)
         */
        $result->setScore($score ? '1:0' : '0:1');
        $result->setWinnerId($score ? $firstTeam->getId() : $secondTime->getId());
        $result->setStage($stage);
        $result->setTournamentId($firstTeam->getDivision()->getTournament()->getId());
        $result->setMatchNumber($number);

        return $result;
    }
}

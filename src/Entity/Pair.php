<?php

declare(strict_types=1);

namespace App\Entity;

readonly class Pair
{
    public function __construct(private Team $firstTeam, private Team $secondTeam)
    {
    }

    public function getFirstTeam(): Team
    {
        return $this->firstTeam;
    }

    public function getSecondTeam(): Team
    {
        return $this->secondTeam;
    }

    public function getWinner(): Team
    {
        return $this->firstTeam->getId() === $this->getFirstTeam()->getPlayoffResults()->last()->getWinnerId()
            ? $this->getFirstTeam()
            : $this->getSecondTeam();
    }
}

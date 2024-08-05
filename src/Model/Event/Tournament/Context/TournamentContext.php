<?php

declare(strict_types=1);

namespace App\Model\Event\Tournament\Context;

readonly class TournamentContext implements ContextInterface
{
    public function __construct(private int $divisionQty, private int $teamQty, private int $playoffTeamQuantity)
    {
    }

    public function getDivisionQuantity(): int
    {
        return $this->divisionQty;
    }

    public function getTeamQuantity(): int
    {
        return $this->teamQty;
    }

    public function getPlayoffTeamQuantity(): int
    {
        return $this->playoffTeamQuantity;
    }
}

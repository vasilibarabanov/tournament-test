<?php

namespace App\Model\Event\Tournament\Result;

use App\Entity\Result;
use App\Entity\Team;

interface ResultSimulatorInterface
{
    public function execute(Team $firstTeam, Team $secondTime, string $stage, int $number = 0): Result;
}

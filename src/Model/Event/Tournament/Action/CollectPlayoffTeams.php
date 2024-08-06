<?php

declare(strict_types=1);

namespace App\Model\Event\Tournament\Action;

use App\Entity\Tournament;
use App\Model\Event\Tournament\Context\ContextInterface;
use App\Model\Event\Tournament\Playoff\Pair;
use App\Repository\TeamRepository;

readonly class CollectPlayoffTeams implements ActionInterface
{
    public function __construct(
        private TeamRepository $teamRepository
    ) {
    }

    public function execute(Tournament $tournament, ContextInterface $context): void
    {
        [$firstDiv, $secondDiv] = $tournament->getDivisions();
        $firstScores = $firstDiv->getPlayoffTeams();
        $secondScores = $secondDiv->getPlayoffTeams();
        $counter = 0;
        $commandQuantity = $context->getPlayoffTeamQuantity();
        while ($counter !== $commandQuantity) {
            $pair = new Pair(
                $this->teamRepository->find($firstScores[$counter]),
                $this->teamRepository->find($secondScores[$commandQuantity - $counter - 1])
            );
            $tournament->addPlayoffPair($pair);
            $counter++;
        }
    }
}

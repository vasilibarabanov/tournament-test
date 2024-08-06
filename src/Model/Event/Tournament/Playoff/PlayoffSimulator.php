<?php

declare(strict_types=1);

namespace App\Model\Event\Tournament\Playoff;

use App\Entity\Tournament;
use App\Model\Event\Tournament\Result\ResultSimulatorInterface;

readonly class PlayoffSimulator
{
    public function __construct(
        private ResultSimulatorInterface $resultSimulator
    ) {
    }

    public function simulate(Tournament $tournament): void
    {
        if ($tournament->getPlayoffPairs()->count() === 0) {
            return;
        }
        $winners = $this->getPairWinners($tournament);
        if (count($winners) === 1) {
            return; // champion
        }
        $this->addNewPairs($tournament, $winners);

        $this->simulate($tournament);
    }

    public function getPairWinners(Tournament $tournament): array
    {
        $winners = [];
        $stage = '1/' . $tournament->getPlayoffPairs()->count();
        $matchNumber = 1;
        foreach ($tournament->getPlayoffPairs() as $pair) {
            $result =
                $this->resultSimulator->execute($pair->getFirstTeam(), $pair->getSecondTeam(), $stage, $matchNumber);
            $pair->getFirstTeam()->addResult($result);
            $tournament->removePlayoffPair($pair);
            $winners[] = $pair->getWinner();
            $matchNumber++;
        }

        return $winners;
    }

    public function addNewPairs(Tournament $tournament, array $winners): void
    {
        do {
            $pair = new Pair(
                current($winners),
                next($winners)
            );
            $tournament->addPlayoffPair($pair);
        } while (next($winners));
    }
}

<?php

declare(strict_types=1);

namespace App\Model\Event\Tournament\Action;

use App\Entity\Division;
use App\Entity\Team;
use App\Entity\Tournament;
use App\Model\Event\Tournament\Context\ContextInterface;
use App\Model\Event\Tournament\Result\ResultSimulatorInterface;
use Doctrine\Common\Collections\Collection;

readonly class GroupStage implements ActionInterface
{
    public const string STAGE = 'group';

    public function __construct(
        private ResultSimulatorInterface $resultSimulator
    ) {
    }

    public function execute(Tournament $tournament, ContextInterface $context): void
    {
        foreach ($tournament->getDivisions() as $division) {
            foreach ($division->getTeams() as $team) {
                $opponentCollection = $this->getOpponentCollection($division, $team);
                foreach ($opponentCollection as $opponent) {
                    $result = $this->resultSimulator->execute($team, $opponent, self::STAGE);
                    $team->addResult($result);
                    $opponent->addResult(clone $result);
                }
            }
        }
    }

    private function getOpponentCollection(Division $division, Team $team): Collection
    {
        return $division->getTeams()->filter(function($opponent) use ($team) {
            /** @var Team $opponent */
            $groupResults = $opponent->getGroupResults();
            if ($groupResults->count()) {
                foreach ($groupResults as $result) {
                    if ($result->getFirstTeamId() === $team->getId()
                        || $result->getSecondTeamId() === $team->getId()
                    ) {
                        return false; // already played
                    }
                }
            }
            return $opponent->getName() !== $team->getName();
        });
    }
}

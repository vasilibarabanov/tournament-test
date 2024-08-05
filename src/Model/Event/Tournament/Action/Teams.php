<?php

declare(strict_types=1);

namespace App\Model\Event\Tournament\Action;

use App\Entity\Team;
use App\Entity\Tournament;
use App\Model\Event\Tournament\Context\ContextInterface;
use Doctrine\ORM\EntityManagerInterface;

readonly class Teams implements ActionInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function execute(Tournament $tournament, ContextInterface $context): void
    {
        $teamCounter = 0;
        $range = range('1', '1000');
        foreach ($tournament->getDivisions() as $division) {
            while ($teamCounter !== $context->getTeamQuantity()) {
                $team = new Team();
                $team->setName('Team ' . current($range));
                $division->addTeam($team);
                $teamCounter++;
                next($range);
            }
            $teamCounter = 0;
        }
        $this->entityManager->persist($tournament);
        $this->entityManager->flush();
    }
}

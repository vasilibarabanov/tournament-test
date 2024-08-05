<?php

declare(strict_types=1);

namespace App\Model\Event\Tournament\Action;

use App\Entity\Division;
use App\Entity\Tournament;
use App\Model\Event\Tournament\Context\ContextInterface;

class Divisions implements ActionInterface
{
    public function execute(Tournament $tournament, ContextInterface $context): void
    {
        $divisionCounter = 0;
        foreach (range('A', 'Z') as $divisionValue) {
            if ($divisionCounter === $context->getDivisionQuantity()) {
                break;
            }
            $division = new Division($context->getTeamQuantity());
            $division->setName('Division ' . $divisionValue);
            $tournament->addDivision($division);
            $divisionCounter++;
        }
    }
}

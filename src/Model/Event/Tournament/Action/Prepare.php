<?php

declare(strict_types=1);

namespace App\Model\Event\Tournament\Action;

use App\Entity\Tournament;
use App\Model\Event\Tournament\Context\ContextInterface;

class Prepare implements ActionInterface
{
    public function execute(Tournament $tournament, ContextInterface $context): void
    {
        $range = range(1930, 2022, 4);
        $year = $range[array_rand($range)];
        $tournament->setName('The ' . $year . ' FIFA World Cup');
    }
}

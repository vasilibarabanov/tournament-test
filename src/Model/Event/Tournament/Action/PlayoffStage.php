<?php

declare(strict_types=1);

namespace App\Model\Event\Tournament\Action;

use App\Entity\Tournament;
use App\Model\Event\Tournament\Context\ContextInterface;
use App\Model\Event\Tournament\Playoff\PlayoffSimulator;

readonly class PlayoffStage implements ActionInterface
{
    public function __construct(
        private PlayoffSimulator $playoffSimulator
    ) {
    }

    public function execute(Tournament $tournament, ContextInterface $context): void
    {
        $this->playoffSimulator->simulate($tournament);
    }
}

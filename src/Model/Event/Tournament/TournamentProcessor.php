<?php

declare(strict_types=1);

namespace App\Model\Event\Tournament;

use App\Entity\Tournament;
use App\Model\Event\Tournament\Action\ActionInterface;
use App\Model\Event\Tournament\Context\ContextInterface;

class TournamentProcessor
{
    /**
     * @var ActionInterface[]
     */
    private array $actions;

    public function __construct(
        array $actions
    ) {
        $this->actions = $actions;
    }

    public function execute(Tournament $tournament, ContextInterface $context): void
    {
        foreach ($this->actions as $action) {
            $action->execute($tournament, $context);
        }
    }
}

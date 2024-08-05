<?php

namespace App\Model\Event\Tournament\Action;

use App\Entity\Tournament;
use App\Model\Event\Tournament\Context\ContextInterface;

interface ActionInterface
{
    public function execute(Tournament $tournament, ContextInterface $context): void;
}

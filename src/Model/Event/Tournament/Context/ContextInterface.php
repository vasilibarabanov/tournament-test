<?php

namespace App\Model\Event\Tournament\Context;

interface ContextInterface
{
    public function getDivisionQuantity(): int;
    public function getTeamQuantity(): int;
    public function getPlayoffTeamQuantity(): int;
}

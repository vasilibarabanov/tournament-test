<?php

declare(strict_types=1);

namespace App\Tests\Model\Event\Tournament\Action;

use App\Entity\Division;
use App\Entity\Tournament;
use App\Model\Event\Tournament\Action\Divisions;
use App\Model\Event\Tournament\Context\ContextInterface;
use PHPUnit\Framework\TestCase;

class DivisionsTest extends TestCase
{
    /** @var Tournament */
    private $tournament;

    /** @var ContextInterface */
    private $context;

    /** @var Divisions */
    private $action;

    protected function setUp(): void
    {
        $this->tournament = $this->createMock(Tournament::class);
        $this->context = $this->createMock(ContextInterface::class);

        $this->action = new Divisions();
    }

    public function testExecute(): void
    {
        $divisionQuantity = 3;
        $teamQuantity = 10;

        $this->context->method('getDivisionQuantity')->willReturn($divisionQuantity);
        $this->context->method('getTeamQuantity')->willReturn($teamQuantity);

        $this->tournament->expects($this->exactly($divisionQuantity))
            ->method('addDivision')
            ->with($this->callback(function (Division $division) use ($teamQuantity) {
                static $index = 0;
                $expectedNames = ['Division A', 'Division B', 'Division C'];
                $this->assertEquals($expectedNames[$index], $division->getName());
                $this->assertEquals($teamQuantity, $division->getTeamQty());
                $index++;
                return true;
            }));

        $this->action->execute($this->tournament, $this->context);
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Model\Event\Tournament\Playoff;

use App\Entity\Team;
use App\Entity\Tournament;
use App\Model\Event\Tournament\Playoff\Pair;
use App\Model\Event\Tournament\Playoff\PlayoffSimulator;
use App\Model\Event\Tournament\Result\ResultSimulatorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class PlayoffSimulatorTest extends TestCase
{
    /** @var ResultSimulatorInterface */
    private $resultSimulator;

    /** @var PlayoffSimulator */
    private $playoffSimulator;

    protected function setUp(): void
    {
        $this->resultSimulator = $this->createMock(ResultSimulatorInterface::class);
        $this->playoffSimulator = new PlayoffSimulator($this->resultSimulator);
    }

    public function testSimulateWithNoPlayoffPairs(): void
    {
        $tournament = $this->createMock(Tournament::class);
        $tournament->method('getPlayoffPairs')->willReturn(new ArrayCollection());

        $tournament->expects($this->never())->method('removePlayoffPair');
        $this->resultSimulator->expects($this->never())->method('execute');

        $this->playoffSimulator->simulate($tournament);
    }

    public function testGetPairWinners(): void
    {
        $tournament = $this->createMock(Tournament::class);
        $team1 = $this->createMock(Team::class);
        $team2 = $this->createMock(Team::class);

        $pair = $this->createMock(Pair::class);
        $pair->method('getFirstTeam')->willReturn($team1);
        $pair->method('getSecondTeam')->willReturn($team2);
        $pair->method('getWinner')->willReturn($team1);

        $pairs = new ArrayCollection([$pair]);

        $tournament->method('getPlayoffPairs')->willReturn($pairs);

        $this->resultSimulator->expects($this->once())->method('execute')->with($team1, $team2, '1/1', 1);

        $team1->expects($this->once())->method('addResult');
        $tournament->expects($this->once())->method('removePlayoffPair')->with($pair);

        $winners = $this->playoffSimulator->getPairWinners($tournament);

        $this->assertSame([$team1], $winners);
    }

    public function testAddNewPairs(): void
    {
        $tournament = $this->createMock(Tournament::class);
        $team1 = $this->createMock(Team::class);
        $team2 = $this->createMock(Team::class);

        $winners = [$team1, $team2];

        $tournament->expects($this->once())->method('addPlayoffPair')->with($this->isInstanceOf(Pair::class));

        $this->playoffSimulator->addNewPairs($tournament, $winners);
    }

    public function testSimulateWithPlayoffPairs(): void
    {
        $tournament = $this->createMock(Tournament::class);
        $team1 = $this->createMock(Team::class);
        $team2 = $this->createMock(Team::class);

        $pair = $this->createMock(Pair::class);
        $pair->method('getFirstTeam')->willReturn($team1);
        $pair->method('getSecondTeam')->willReturn($team2);
        $pair->method('getWinner')->willReturn($team1);

        $pairs = new ArrayCollection([$pair]);

        $tournament->method('getPlayoffPairs')->willReturn($pairs);

        $this->resultSimulator->expects($this->once())->method('execute')->with($team1, $team2, '1/1', 1);

        $team1->expects($this->once())->method('addResult');
        $tournament->expects($this->once())->method('removePlayoffPair')->with($pair);

        $this->playoffSimulator->simulate($tournament);
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Model\Event\Tournament;

use App\Entity\Tournament;
use App\Model\Event\Tournament\Action\GroupStage;
use App\Model\Event\Tournament\DataProvider as TournamentDataProvider;
use App\Model\Event\Tournament\TournamentProcessor;
use App\Repository\ResultRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DataProviderTest extends TestCase
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ResultRepository */
    private $resultRepository;

    /** @var TeamRepository */
    private $teamRepository;

    /** @var TournamentDataProvider */
    private $dataProvider;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->resultRepository = $this->createMock(ResultRepository::class);
        $this->teamRepository = $this->createMock(TeamRepository::class);

        $this->dataProvider = new TournamentDataProvider(
            $this->entityManager,
            $this->createMock(TournamentProcessor::class),
            $this->resultRepository,
            $this->teamRepository
        );
    }

    #[DataProvider('getTestData')] public function testGetResults(
        array $groupBestTeams,
        array $playoffTeams,
        array $playoffWinners,
        array $expectedResults
    ): void
    {
        $tournamentId = 1;
        $tournament = $this->createMock(Tournament::class);
        $tournament->method('getId')->willReturn($tournamentId);

        $stages = ['quarterfinal', 'semifinal', 'final'];

        $this->teamRepository->method('findBestTeams')
            ->willReturnMap([
                [$tournamentId, [GroupStage::STAGE], $groupBestTeams],
                [$tournamentId, $stages, $playoffWinners]
            ]);

        $this->teamRepository->method('findPlayoffTeams')
            ->with($tournamentId, $stages)
            ->willReturn($playoffTeams);

        $results = $this->dataProvider->getResults($tournament, $stages);

        $this->assertEquals($expectedResults, $results);
    }

    public static function getTestData(): iterable
    {
        yield [
            ['team1', 'team2', 'team3', 'team4', 'team5', 'team6'],
            ['team2', 'team3', 'team4', 'team5'],
            ['team2', 'team4'],
            [
                'team2', // playoff winner
                'team4', // playoff winner
                'team3', // playoff loser
                'team5', // playoff loser
                'team1',  // group stage without playoff
                'team6',  // group stage without playoff
            ]
        ];

        yield [
            ['11', '21', '31', '33', '44', '66'],
            ['33', '44'],
            ['44'],
            [
                '44', // playoff winner
                '33', // playoff loser
                '11', // group stage without playoff
                '21', // group stage without playoff
                '31',  // group stage without playoff
                '66',  // group stage without playoff
            ]
        ];
    }
}
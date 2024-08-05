<?php

declare(strict_types=1);

namespace App\Model\Event\Tournament;

use App\Entity\Tournament;
use App\Model\Event\Tournament\Action\GroupStage;
use App\Model\Event\Tournament\Context\TournamentContext;
use App\Repository\ResultRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class DataProvider
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TournamentProcessor $tournamentProcessor,
        private ResultRepository $resultRepository,
        private TeamRepository $teamRepository
    ) {
    }

    public function get(): array
    {
        $tournament = $this->createTournament();
        $stages = $this->resultRepository->findPlayoffStages($tournament->getId());
        $playoffData = [];
        foreach ($stages as $stage) {
            $playoffData[$stage] = $this->resultRepository->findByStage($tournament->getId(), $stage);
        }

        return [
            'tournament'  => $tournament,
            'playoffData' => $playoffData,
            'results'     => $this->getResults($tournament, $stages)
        ];
    }

    public function createTournament(): Tournament
    {
        /**
         * Хорошо было бы сделать action валидации, так как при определённых условиях,
         * например нечётное число команд/команд в плэй-офф/дивизионов не ясно как должен процесситься турнир,
         * но так как в задаче ничего не сказано про возможность модификации опций турнира,
         * то и добавлять лишние проверки не стал.
         *
         * Так же на фронте не учитывал варианты с playoffTeamQuantity > 8.
         */
        $tournamentContext = new TournamentContext(2, 8, 4);
        $tournament =
            new Tournament($tournamentContext->getDivisionQuantity(), $tournamentContext->getPlayoffTeamQuantity());
        $this->tournamentProcessor->execute($tournament, $tournamentContext);
        $this->entityManager->persist($tournament);
        $this->entityManager->flush();

        return $tournament;
    }

    public function getResults($tournament, array $stages): array
    {
        $groupBestTeams = $this->teamRepository->findBestTeams($tournament->getId(), [GroupStage::STAGE]);
        $playoffTeams = $this->teamRepository->findPlayoffTeams($tournament->getId(), $stages);
        $playoffWinners = $this->teamRepository->findBestTeams($tournament->getId(), $stages);

        $groupStageWithoutPlayoff = array_diff($groupBestTeams, $playoffTeams);
        $playoffStageLosers = array_values(array_diff($playoffTeams, $playoffWinners));

        return array_merge($playoffWinners, $playoffStageLosers, $groupStageWithoutPlayoff);
    }
}

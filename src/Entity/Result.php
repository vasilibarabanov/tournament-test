<?php

namespace App\Entity;

use App\Repository\ResultRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResultRepository::class)]
#[ORM\Index(fields: ['stage'])]
class Result
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstTeam = null;

    #[ORM\Column(length: 255)]
    private ?string $secondTeam = null;

    #[ORM\Column]
    private ?int $firstTeamId = null;

    #[ORM\Column]
    private ?int $secondTeamId = null;

    #[ORM\ManyToOne(inversedBy: 'results')]
    private ?Team $Team = null;

    #[ORM\Column(length: 255)]
    private ?string $score = null;

    #[ORM\Column(length: 255)]
    private ?string $stage = null;

    #[ORM\Column]
    private ?int $winnerId = null;

    #[ORM\Column]
    private ?int $tournamentId = null;

    #[ORM\Column]
    private ?int $matchNumber = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getFirstTeam(): ?string
    {
        return $this->firstTeam;
    }

    public function setFirstTeam(string $firstTeam): static
    {
        $this->firstTeam = $firstTeam;

        return $this;
    }

    public function getSecondTeam(): ?string
    {
        return $this->secondTeam;
    }

    public function setSecondTeam(string $secondTeam): static
    {
        $this->secondTeam = $secondTeam;

        return $this;
    }

    public function getFirstTeamId(): ?int
    {
        return $this->firstTeamId;
    }

    public function setFirstTeamId(int $firstTeamId): static
    {
        $this->firstTeamId = $firstTeamId;

        return $this;
    }

    public function getSecondTeamId(): ?int
    {
        return $this->secondTeamId;
    }

    public function setSecondTeamId(int $secondTeamId): static
    {
        $this->secondTeamId = $secondTeamId;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->Team;
    }

    public function setTeam(?Team $Team): static
    {
        $this->Team = $Team;

        return $this;
    }

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function setScore(string $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getStage(): ?string
    {
        return $this->stage;
    }

    public function setStage(string $stage): static
    {
        $this->stage = $stage;

        return $this;
    }

    public function getWinnerId(): ?int
    {
        return $this->winnerId;
    }

    public function setWinnerId(int $winnerId): static
    {
        $this->winnerId = $winnerId;

        return $this;
    }

    public function getTournamentId(): ?int
    {
        return $this->tournamentId;
    }

    public function setTournamentId(int $tournamentId): static
    {
        $this->tournamentId = $tournamentId;

        return $this;
    }

    public function getMatchNumber(): ?int
    {
        return $this->matchNumber;
    }

    public function setMatchNumber(int $matchNumber): static
    {
        $this->matchNumber = $matchNumber;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\DivisionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DivisionRepository::class)]
class Division
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Team>
     */
    #[ORM\OneToMany(targetEntity: Team::class, mappedBy: 'division', cascade: ['persist', 'remove'])]
    private Collection $teams;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'], inversedBy: 'divisions')]
    private ?Tournament $tournament = null;

    #[ORM\Column]
    private ?int $teamQty;

    private array $playoffTeams;

    private array $scores;

    public function __construct(int $teamQty)
    {
        $this->teamQty = $teamQty;
        $this->teams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Team>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): static
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
            $team->setDivision($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): static
    {
        if ($this->teams->removeElement($team)) {
            // set the owning side to null (unless already changed)
            if ($team->getDivision() === $this) {
                $team->setDivision(null);
            }
        }

        return $this;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): static
    {
        $this->tournament = $tournament;

        return $this;
    }

    public function getTeamQty(): ?int
    {
        return $this->teamQty;
    }

    public function getScores(): array
    {
        if (!empty($this->scores)) {
            return $this->scores;
        }
        foreach ($this->getTeams() as $team) {
            $this->scores[$team->getId()] = $team->getWinsQuantity();
        }

        return $this->scores;
    }

    public function getPlayoffTeams(): array
    {
        if (!empty($this->playoffTeams)) {
            return $this->playoffTeams;
        }
        foreach ($this->getTeams() as $team) {
            $this->playoffTeams[$team->getId()] = $team->getWinsQuantity();
        }
        arsort($this->playoffTeams);

        return array_keys($this->playoffTeams);
    }
}

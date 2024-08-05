<?php

namespace App\Entity;

use App\Repository\TournamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TournamentRepository::class)]
class Tournament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Division>
     */
    #[ORM\OneToMany(targetEntity: Division::class, mappedBy: 'tournament', cascade: ['persist', 'remove'])]
    private Collection $divisions;

    #[ORM\Column]
    private ?int $divisionQty;

    #[ORM\Column]
    private ?int $playoffTeamQty;

    private Collection $pairs;

    public function __construct(int $divisionQty, int $playoffTeamQty)
    {
        $this->divisionQty = $divisionQty;
        $this->playoffTeamQty = $playoffTeamQty;
        $this->pairs = new ArrayCollection();
        $this->divisions = new ArrayCollection();
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
     * @return Collection<int, Division>
     */
    public function getDivisions(): Collection
    {
        return $this->divisions;
    }

    public function addDivision(Division $division): static
    {
        if (!$this->divisions->contains($division)) {
            $this->divisions->add($division);
            $division->setTournament($this);
        }

        return $this;
    }

    public function removeDivision(Division $division): static
    {
        if ($this->divisions->removeElement($division)) {
            // set the owning side to null (unless already changed)
            if ($division->getTournament() === $this) {
                $division->setTournament(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Pair>
     */
    public function getPlayoffPairs(): Collection
    {
        return $this->pairs;
    }

    public function addPlayoffPair(Pair $pair): static
    {
        if (!$this->pairs->contains($pair)) {
            $this->pairs->add($pair);
        }

        return $this;
    }

    public function removePlayoffPair(Pair $pair): static
    {
        $this->pairs->removeElement($pair);

        return $this;
    }

    public function getDivisionQty(): ?int
    {
        return $this->divisionQty;
    }

    public function getPlayoffTeamQty(): ?int
    {
        return $this->playoffTeamQty;
    }
}

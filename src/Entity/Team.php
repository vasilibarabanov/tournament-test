<?php

namespace App\Entity;

use App\Model\Event\Tournament\Action\GroupStage;
use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'], inversedBy: 'teams')]
    private ?Division $division = null;

    /**
     * @var Collection<int, Result>
     */
    #[ORM\OneToMany(targetEntity: Result::class, mappedBy: 'Team', cascade: ['persist', 'remove'])]
    private Collection $results;

    public function __construct()
    {
        $this->results = new ArrayCollection();
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

    public function getDivision(): ?Division
    {
        return $this->division;
    }

    public function setDivision(?Division $division): static
    {
        $this->division = $division;

        return $this;
    }

    /**
     * @return Collection<int, Result>
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    /**
     * @return Collection<int, Result>
     */
    public function getGroupResults(): Collection
    {
        return $this->results->filter(function ($result) {
            return $result->getStage() === GroupStage::STAGE;
        });
    }

    /**
     * @return Collection<int, Result>
     */
    public function getPlayoffResults(): Collection
    {
        return $this->results->filter(function ($result) {
            return $result->getStage() !== GroupStage::STAGE;
        });
    }

    public function addResult(Result $result): static
    {
        if (!$this->results->contains($result)) {
            $this->results->add($result);
            $result->setTeam($this);
        }

        return $this;
    }

    public function removeResult(Result $result): static
    {
        if ($this->results->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getTeam() === $this) {
                $result->setTeam(null);
            }
        }

        return $this;
    }

    public function getWinsQuantity(): int
    {
        return $this->getGroupResults()->filter(function ($element) {
            /** @var Result $element */
            return $this->getId() === $element->getWinnerId();
        })->count();
    }
}

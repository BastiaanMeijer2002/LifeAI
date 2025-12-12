<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MuscleGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MuscleGroupRepository::class)]
#[ApiResource]
class MuscleGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Muscle>
     */
    #[ORM\OneToMany(targetEntity: Muscle::class, mappedBy: 'MuscleGroup')]
    private Collection $muscles;

    public function __construct()
    {
        $this->muscles = new ArrayCollection();
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
     * @return Collection<int, Muscle>
     */
    public function getMuscles(): Collection
    {
        return $this->muscles;
    }

    public function addMuscle(Muscle $muscle): static
    {
        if (!$this->muscles->contains($muscle)) {
            $this->muscles->add($muscle);
            $muscle->setMuscleGroup($this);
        }

        return $this;
    }

    public function removeMuscle(Muscle $muscle): static
    {
        if ($this->muscles->removeElement($muscle)) {
            // set the owning side to null (unless already changed)
            if ($muscle->getMuscleGroup() === $this) {
                $muscle->setMuscleGroup(null);
            }
        }

        return $this;
    }
}

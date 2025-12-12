<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MuscleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MuscleRepository::class)]
#[ApiResource]
class Muscle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'muscles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MuscleGroup $MuscleGroup = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    /**
     * @var Collection<int, Exercise>
     */
    #[ORM\ManyToMany(targetEntity: Exercise::class, mappedBy: 'muscle')]
    private Collection $exercises;

    public function __construct()
    {
        $this->exercises = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMuscleGroup(): ?MuscleGroup
    {
        return $this->MuscleGroup;
    }

    public function setMuscleGroup(?MuscleGroup $MuscleGroup): static
    {
        $this->MuscleGroup = $MuscleGroup;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    /**
     * @return Collection<int, Exercise>
     */
    public function getExercises(): Collection
    {
        return $this->exercises;
    }

    public function addExercise(Exercise $exercise): static
    {
        if (!$this->exercises->contains($exercise)) {
            $this->exercises->add($exercise);
            $exercise->addMuscle($this);
        }

        return $this;
    }

    public function removeExercise(Exercise $exercise): static
    {
        if ($this->exercises->removeElement($exercise)) {
            $exercise->removeMuscle($this);
        }

        return $this;
    }
}

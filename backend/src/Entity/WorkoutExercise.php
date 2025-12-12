<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\WorkoutExerciseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkoutExerciseRepository::class)]
#[ApiResource]
class WorkoutExercise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Workout $workout = null;

    #[ORM\ManyToOne(inversedBy: 'workoutExercises')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Exercise $exercise = null;

    #[ORM\Column]
    private ?int $repetition = null;

    #[ORM\Column]
    private ?int $weight = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorkout(): ?Workout
    {
        return $this->workout;
    }

    public function setWorkout(?Workout $workout): static
    {
        $this->workout = $workout;

        return $this;
    }

    public function getExercise(): ?Exercise
    {
        return $this->exercise;
    }

    public function setExercise(?Exercise $exercise): static
    {
        $this->exercise = $exercise;

        return $this;
    }

    public function getRepetition(): ?int
    {
        return $this->repetition;
    }

    public function setRepetition(int $repetition): static
    {
        $this->repetition = $repetition;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): static
    {
        $this->weight = $weight;

        return $this;
    }
}

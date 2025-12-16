<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\State\WorkoutProcessor;
use App\Repository\WorkoutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkoutRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            processor: WorkoutProcessor::class
        ),
        new Get(),
        new GetCollection(),
    ]
)]
class Workout
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $start = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $endtime = null;

    /**
     * @var Collection<int, WorkoutExercise>
     */
    #[ORM\OneToMany(targetEntity: WorkoutExercise::class, mappedBy: 'workout')]
    private Collection $exercises;

    #[ApiProperty(writable: false)]
    #[ORM\ManyToOne(inversedBy: 'workouts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    public function __construct()
    {
        $this->exercises = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?\DateTime
    {
        return $this->start;
    }

    public function setStart(\DateTime $start): static
    {
        $this->start = $start;

        return $this;
    }

    public function getEndtime(): ?\DateTime
    {
        return $this->endtime;
    }

    public function setEndtime(?\DateTime $endtime): static
    {
        $this->endtime = $endtime;

        return $this;
    }

    /**
     * @return Collection<int, WorkoutExercise>
     */
    public function getExercises(): Collection
    {
        return $this->exercises;
    }

    public function addExercise(WorkoutExercise $exercise): static
    {
        if (!$this->exercises->contains($exercise)) {
            $this->exercises->add($exercise);
            $exercise->setWorkout($this);
        }

        return $this;
    }

    public function removeExercise(WorkoutExercise $exercise): static
    {
        if ($this->exercises->removeElement($exercise)) {
            // set the owning side to null (unless already changed)
            if ($exercise->getWorkout() === $this) {
                $exercise->setWorkout(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}

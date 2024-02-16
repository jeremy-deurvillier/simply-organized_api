<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\StateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: StateRepository::class)]
#[ApiResource(
    security: "is_granted('ROLE_USER')",
    denormalizationContext: ["groups" => ["create:state", "update:state"]],
    normalizationContext: ["groups" => ["read:state"]]
)]
class State
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["read:state"])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(["create:state", "update:state", "read:state"])]
    private ?bool $progress = null;

    #[ORM\Column]
    #[Groups(["create:state", "update:state", "read:state"])]
    private ?bool $done = null;

    #[ORM\Column]
    #[Groups(["create:state", "update:state", "read:state"])]
    private ?bool $canceled = null;

    #[ORM\OneToMany(mappedBy: 'state', targetEntity: Activity::class)]
    #[Groups(["read:state"])]
    private Collection $activities;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isProgress(): ?bool
    {
        return $this->progress;
    }

    public function setProgress(bool $progress): static
    {
        $this->progress = $progress;

        return $this;
    }

    public function isDone(): ?bool
    {
        return $this->done;
    }

    public function setDone(bool $done): static
    {
        $this->done = $done;

        return $this;
    }

    public function isCanceled(): ?bool
    {
        return $this->canceled;
    }

    public function setCanceled(bool $canceled): static
    {
        $this->canceled = $canceled;

        return $this;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): static
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
            $activity->setState($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): static
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getState() === $this) {
                $activity->setState(null);
            }
        }

        return $this;
    }
}

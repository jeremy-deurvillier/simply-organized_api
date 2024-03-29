<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\EisenhowerMatrixRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: EisenhowerMatrixRepository::class)]
#[ApiResource(
    security: "is_granted('ROLE_USER')",
    denormalizationContext: ["groups" => ["create:eisenhower", "update:eisenhower"]],
    normalizationContext: ["groups" => ["read:eisenhower"]]
)]
class EisenhowerMatrix
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["read:eisenhower"])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(["create:eisenhower", "update:eisenhower", "read:eisenhower"])]
    private ?bool $hight = null;

    #[ORM\Column]
    #[Groups(["create:eisenhower", "update:eisenhower", "read:eisenhower"])]
    private ?bool $imperative = null;

    #[ORM\OneToMany(mappedBy: 'eisenhower_matrix', targetEntity: Activity::class)]
    #[Groups(["read:eisenhower"])]
    private Collection $activities;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isHight(): ?bool
    {
        return $this->hight;
    }

    public function setHight(bool $hight): static
    {
        $this->hight = $hight;

        return $this;
    }

    public function isImperative(): ?bool
    {
        return $this->imperative;
    }

    public function setImperative(bool $imperative): static
    {
        $this->imperative = $imperative;

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
            $activity->setEisenhowerMatrix($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): static
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getEisenhowerMatrix() === $this) {
                $activity->setEisenhowerMatrix(null);
            }
        }

        return $this;
    }
}

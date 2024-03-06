<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\ActivityRepository;
use App\State\ActivityProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
#[ApiResource(
    security: "is_granted('ROLE_USER')",
    denormalizationContext: ["groups" => ["create:activity", "update:activity"], "jsonld_embed_context" => true],
    normalizationContext: ["groups" => ["read:activity"]]
)]
// #[Post(processor: ActivityProcessor::class)]
#[Get()]
#[GetCollection()]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["read:project", "read:activity"])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Groups(["create:project", "update:project", "read:project", "create:activity", "update:activity", "read:activity"])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["create:project", "update:project", "read:project", "create:activity", "update:activity", "read:activity"])]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["create:project", "update:project", "create:activity", "update:activity", "read:activity"])]
    private ?\DateTimeImmutable $datetime = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["create:project", "update:project", "read:project", "create:activity", "update:activity", "read:activity"])]
    private ?int $duration = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["create:project", "update:project", "read:project", "create:activity", "update:activity", "read:activity"])]
    private ?string $recurrence_rule = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["create:project", "update:project", "read:project", "create:activity", "update:activity", "read:activity"])]
    private ?string $alarm_rule = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'activities', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["create:project", "update:project", "read:activity"])]
    private ?Project $project = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'activities', cascade: ['persist'])]
    #[Groups(["create:project", "update:project", "read:project", "create:activity", "update:activity", "read:activity"])]
    private Collection $categories;

    #[ORM\Column(nullable: true)]
    #[Groups("read:project")]
    private ?\DateTimeImmutable $date_last_state = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    #[Groups(["update:activity", "read:activity"])]
    private ?EisenhowerMatrix $eisenhower_matrix = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    #[Groups(["update:activity", "read:activity"])]
    private ?State $state = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDatetime(): ?\DateTimeImmutable
    {
        return $this->datetime;
    }

    public function setDatetime(?\DateTimeImmutable $datetime): static
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getRecurrenceRule(): ?string
    {
        return $this->recurrence_rule;
    }

    public function setRecurrenceRule(?string $recurrence_rule): static
    {
        $this->recurrence_rule = $recurrence_rule;

        return $this;
    }

    public function getAlarmRule(): ?string
    {
        return $this->alarm_rule;
    }

    public function setAlarmRule(?string $alarm_rule): static
    {
        $this->alarm_rule = $alarm_rule;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getDateLastState(): ?\DateTimeImmutable
    {
        return $this->date_last_state;
    }

    public function setDateLastState(?\DateTimeImmutable $date_last_state): static
    {
        $this->date_last_state = $date_last_state;

        return $this;
    }

    public function getEisenhowerMatrix(): ?EisenhowerMatrix
    {
        return $this->eisenhower_matrix;
    }

    public function setEisenhowerMatrix(?EisenhowerMatrix $eisenhower_matrix): static
    {
        $this->eisenhower_matrix = $eisenhower_matrix;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): static
    {
        $this->state = $state;

        return $this;
    }
}

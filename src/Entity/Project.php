<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\ProjectRepository;
use App\State\ProjectProcessor;
use App\State\ProjectProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ApiResource(
    security: "is_granted('ROLE_USER')",
    denormalizationContext: ["groups" => ["create:project", "update:project"], "jsonld_embed_context" => true, "enable_max_depth" => true],
    normalizationContext: ["groups" => ["read:project"]]
)]
#[Post(processor: ProjectProcessor::class)]
#[Patch(processor: ProjectProcessor::class)]
#[Get(provider: ProjectProvider::class)]
#[GetCollection(provider: ProjectProvider::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["read:project"])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Groups(["create:project", "update:project", "read:project"])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["create:project", "update:project", "read:project"])]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'projects', cascade: ['persist'])]
    #[Groups(["create:project", "update:project", "read:project"])]
    #[ApiProperty(readableLink: true, writableLink: true, fetchEager: true)]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Activity::class, orphanRemoval: true, cascade: ['persist'])]
    #[Groups(["create:project", "update:project", "read:project"])]
    #[ApiProperty(readableLink: true, writableLink: true, fetchEager: true)]
    private Collection $activities;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'member_of')]
    #[Groups(["read:project"])]
    private Collection $members;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["create:project", "update:project", "read:project"])]
    private ?string $list = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->activities = new ArrayCollection();
        $this->members = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

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
            $activity->setProject($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): static
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getProject() === $this) {
                $activity->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(User $member): static
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
        }

        return $this;
    }

    public function removeMember(User $member): static
    {
        $this->members->removeElement($member);

        return $this;
    }

    public function getList(): ?string
    {
        return $this->list;
    }

    public function setList(?string $list): static
    {
        $this->list = $list;

        return $this;
    }
}

<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ApiResource(
    denormalizationContext: ["groups" => ["create:event", "update:event"]],
    normalizationContext: ["groups" => ["read:event"]]
)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["read:event"])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Groups(["create:event", "update:event", "read:event"])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["create:event", "update:event", "read:event"])]
    private ?string $note = null;

    #[ORM\Column]
    #[Groups(["create:event", "update:event", "read:event"])]
    private ?\DateTimeImmutable $date_start = null;

    #[ORM\Column]
    #[Groups(["create:event", "update:event", "read:event"])]
    private ?\DateTimeImmutable $date_end = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["create:event", "update:event", "read:event"])]
    private ?string $recurrence_rule = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["create:event", "update:event", "read:event"])]
    private ?string $alarm_rule = null;

    #[ORM\Column(length: 200, nullable: true)]
    #[Groups(["create:event", "update:event", "read:event"])]
    private ?string $location = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["read:event"])]
    private ?string $shared_url = null;

    #[ORM\ManyToMany(targetEntity: Resource::class, inversedBy: 'events')]
    #[Groups(["read:event"])]
    private Collection $resources;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'event_invitations')]
    #[Groups(["read:event"])]
    private Collection $guests;

    public function __construct()
    {
        $this->resources = new ArrayCollection();
        $this->guests = new ArrayCollection();
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

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getDateStart(): ?\DateTimeImmutable
    {
        return $this->date_start;
    }

    public function setDateStart(\DateTimeImmutable $date_start): static
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeImmutable
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTimeImmutable $date_end): static
    {
        $this->date_end = $date_end;

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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

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

    public function getSharedUrl(): ?string
    {
        return $this->shared_url;
    }

    public function setSharedUrl(?string $shared_url): static
    {
        $this->shared_url = $shared_url;

        return $this;
    }

    /**
     * @return Collection<int, Resource>
     */
    public function getResources(): Collection
    {
        return $this->resources;
    }

    public function addResource(Resource $resource): static
    {
        if (!$this->resources->contains($resource)) {
            $this->resources->add($resource);
        }

        return $this;
    }

    public function removeResource(Resource $resource): static
    {
        $this->resources->removeElement($resource);

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
     * @return Collection<int, User>
     */
    public function getGuests(): Collection
    {
        return $this->guests;
    }

    public function addGuest(User $guest): static
    {
        if (!$this->guests->contains($guest)) {
            $this->guests->add($guest);
        }

        return $this;
    }

    public function removeGuest(User $guest): static
    {
        $this->guests->removeElement($guest);

        return $this;
    }
}

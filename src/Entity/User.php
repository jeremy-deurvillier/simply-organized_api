<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource()]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 180)]
    private ?string $alias = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Quicknote::class, orphanRemoval: true)]
    private Collection $quicknotes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Event::class, orphanRemoval: true)]
    private Collection $events;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Project::class, orphanRemoval: true)]
    private Collection $projects;

    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'members')]
    private Collection $member_of;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'guests')]
    private Collection $event_invitations;

    public function __construct()
    {
        $this->quicknotes = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->member_of = new ArrayCollection();
        $this->event_invitations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): static
    {
        $this->alias = $alias;

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

    /**
     * @return Collection<int, Quicknote>
     */
    public function getQuicknotes(): Collection
    {
        return $this->quicknotes;
    }

    public function addQuicknote(Quicknote $quicknote): static
    {
        if (!$this->quicknotes->contains($quicknote)) {
            $this->quicknotes->add($quicknote);
            $quicknote->setUser($this);
        }

        return $this;
    }

    public function removeQuicknote(Quicknote $quicknote): static
    {
        if ($this->quicknotes->removeElement($quicknote)) {
            // set the owning side to null (unless already changed)
            if ($quicknote->getUser() === $this) {
                $quicknote->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setUser($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getUser() === $this) {
                $event->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setUser($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getUser() === $this) {
                $project->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getMemberOf(): Collection
    {
        return $this->member_of;
    }

    public function addMemberOf(Project $memberOf): static
    {
        if (!$this->member_of->contains($memberOf)) {
            $this->member_of->add($memberOf);
            $memberOf->addMember($this);
        }

        return $this;
    }

    public function removeMemberOf(Project $memberOf): static
    {
        if ($this->member_of->removeElement($memberOf)) {
            $memberOf->removeMember($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEventInvitations(): Collection
    {
        return $this->event_invitations;
    }

    public function addEventInvitation(Event $eventInvitation): static
    {
        if (!$this->event_invitations->contains($eventInvitation)) {
            $this->event_invitations->add($eventInvitation);
            $eventInvitation->addGuest($this);
        }

        return $this;
    }

    public function removeEventInvitation(Event $eventInvitation): static
    {
        if ($this->event_invitations->removeElement($eventInvitation)) {
            $eventInvitation->removeGuest($this);
        }

        return $this;
    }
}

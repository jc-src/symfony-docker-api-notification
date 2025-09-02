<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $email = null;

    /**
     * @var Collection<int, Notification>
     */
    #[ORM\OneToMany(mappedBy: 'contactTo', targetEntity: Notification::class)]
    private Collection $notificationTo;

    /**
     * @var Collection<int, Notification>
     */
    #[ORM\OneToMany(mappedBy: 'contactFrom', targetEntity: Notification::class)]
    private Collection $notificationFrom;

    public function __construct()
    {
        $this->notificationTo = new ArrayCollection();
        $this->notificationFrom = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getContactTo(): Collection
    {
        return $this->notificationTo;
    }

    public function addContactTo(Notification $notificationTo): static
    {
        if (!$this->notificationTo->contains($notificationTo)) {
            $this->notificationTo->add($notificationTo);
            $notificationTo->setContactFrom($this);
        }

        return $this;
    }

    public function removeContactTo(Notification $notificationTo): static
    {
        if ($this->notificationTo->removeElement($notificationTo)) {
            // set the owning side to null (unless already changed)
            if ($notificationTo->getContactFrom() === $this) {
                $notificationTo->setContactFrom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotificationFrom(): Collection
    {
        return $this->notificationFrom;
    }

    public function addNotificationFrom(Notification $notificationFrom): static
    {
        if (!$this->notificationFrom->contains($notificationFrom)) {
            $this->notificationFrom->add($notificationFrom);
            $notificationFrom->setContactTo($this);
        }

        return $this;
    }

    public function removeNotificationFrom(Notification $notificationFrom): static
    {
        if ($this->notificationFrom->removeElement($notificationFrom)) {
            // set the owning side to null (unless already changed)
            if ($notificationFrom->getContactTo() === $this) {
                $notificationFrom->setContactTo(null);
            }
        }

        return $this;
    }
}

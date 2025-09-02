<?php

namespace App\Entity;

use App\Model\EnumNotificationStatus;
use App\Model\EnumNotificationTypes;
use App\Repository\NotificationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: EnumNotificationTypes::class)]
    private ?EnumNotificationTypes $type = null;

    #[ORM\ManyToOne(targetEntity: Contact::class, cascade: ['persist'], inversedBy: 'notificationFrom')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contact $contactFrom = null;

    #[ORM\ManyToOne(targetEntity: Contact::class, cascade: ['persist'], inversedBy: 'notificationTo')]
    #[ORM\JoinColumn(nullable: false,)]
    private ?Contact $contactTo = null;

    #[ORM\Column(enumType: EnumNotificationStatus::class)]
    private ?EnumNotificationStatus $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $subject = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContactFrom(): ?Contact
    {
        return $this->contactFrom;
    }

    public function setContactFrom(?Contact $contactFrom): static
    {
        $this->contactFrom = $contactFrom;

        return $this;
    }

    public function getContactTo(): ?Contact
    {
        return $this->contactTo;
    }

    public function setContactTo(?Contact $contactTo): static
    {
        $this->contactTo = $contactTo;

        return $this;
    }

    public function getStatus(): ?EnumNotificationStatus
    {
        return $this->status;
    }

    public function setStatus(EnumNotificationStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getType(): ?EnumNotificationTypes
    {
        return $this->type;
    }

    public function setType(?EnumNotificationTypes $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}

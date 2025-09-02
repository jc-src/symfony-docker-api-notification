<?php
declare(strict_types=1);

namespace App\Dto\Notification;

use App\Dto\DtoInterface;
use App\Model\EnumNotificationTypes;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractNotificationDto implements DtoInterface, NotificationInterface
{
    const NOTIFICATION_TYPE = null;

    #[Assert\Valid]
    private ?ContactDto $from = null;

    #[Assert\Valid]
    private ?ContactDto $to = null;

    #[Assert\Length(min: 3, max: 255)]
    private ?string $body = null;

    public function getType(): ?EnumNotificationTypes
    {
        return $this::NOTIFICATION_TYPE;
    }

    public function getFrom(): ?ContactDto
    {
        return $this->from;
    }

    public function setFrom(?ContactDto $from): void
    {
        $this->from = $from;
    }

    public function getTo(): ?ContactDto
    {
        return $this->to;
    }

    public function setTo(?ContactDto $to): void
    {
        $this->to = $to;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): void
    {
        $this->subject = $subject;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): void
    {
        $this->body = $body;
    }
}

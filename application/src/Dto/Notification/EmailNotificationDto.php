<?php
declare(strict_types=1);

namespace App\Dto\Notification;

use App\Model\EnumNotificationTypes;
use Symfony\Component\Validator\Constraints as Assert;

class EmailNotificationDto extends AbstractNotificationDto
{
    const NOTIFICATION_TYPE = EnumNotificationTypes::email;

    #[Assert\Length(min: 3, max: 255)]
    private ?string $subject = null;

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): void
    {
        $this->subject = $subject;
    }
}

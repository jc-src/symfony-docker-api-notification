<?php

declare(strict_types=1);

namespace App\Dto\Notification;

interface NotificationInterface
{
    const TYPE = 'unknown';

    public function getFrom(): ?ContactDto;
    public function getTo(): ?ContactDto;
    public function getBody(): ?string;

}

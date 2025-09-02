<?php
declare(strict_types=1);

namespace App\Dto\Notification;

use App\Model\EnumNotificationStatus;
use App\Model\EnumNotificationTypes;

class NotificationListItemDto
{
    public function __construct(
        public int $id,
        public EnumNotificationTypes $type,
        public EnumNotificationStatus $status,
        public string $from,
        public string $to,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace App\Model;

enum EnumNotificationStatus: string
{
    case pending = 'pending';
    case sent = 'sent';
    case failed = 'failed';
}

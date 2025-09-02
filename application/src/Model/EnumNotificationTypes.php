<?php

declare(strict_types=1);

namespace App\Model;

enum EnumNotificationTypes: string
{
    case email = 'email';
    case sms = 'sms';
    case mms = 'mms';
}

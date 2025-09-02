<?php

declare(strict_types=1);

namespace App\Dto\Exception;

use App\Dto\DtoInterface;

class SimpleError implements DtoInterface
{
    private int $code = 0;

    private string $message = '';

    public function __construct(string $message, int $code)
    {
        $this->code = $code;
        $this->message = $message;
    }
}

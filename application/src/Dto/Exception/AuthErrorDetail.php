<?php

namespace App\Dto\Exception;

use App\Dto\DtoInterface;

class AuthErrorDetail implements DtoInterface
{
    private array $errors = [];

    public function addError(string $type, string $errorMessage): void
    {
        $this->errors[$type] = $errorMessage;
    }
}

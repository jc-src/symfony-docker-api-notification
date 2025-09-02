<?php

namespace App\Dto\Exception;

use App\Dto\DtoInterface;

class ConstraintErrorMessage implements DtoInterface
{
    private string $name = '';

    private array $errorMessages = [];

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getErrorMessages(): array
    {
        return $this->errorMessages;
    }

    public function setErrorMessages(array $errorMessages): void
    {
        $this->errorMessages = $errorMessages;
    }
}

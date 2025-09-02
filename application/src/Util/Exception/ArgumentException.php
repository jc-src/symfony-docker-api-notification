<?php

namespace App\Util\Exception;

use InvalidArgumentException;

class ArgumentException extends InvalidArgumentException
{
    private array $argumentsMessage = [];

    public function __construct(...$argumentsMessage)
    {
        $this->argumentsMessage = $argumentsMessage;

        parent::__construct('');
    }

    public function getArgumentsMessage(): array
    {
        return $this->argumentsMessage;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}

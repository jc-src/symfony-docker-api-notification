<?php

namespace App\Util\Exception;

use App\Dto\Exception\SimpleError;
use Exception;

class SimpleException extends Exception implements TranslatableExceptionInterface
{
    protected int $statusCode;

    /**
     * @var string[] $argumentsMessage
     */
    private array $argumentsMessage = [];

    public function __construct(int $statusCode, string ...$argumentsMessage)
    {
        parent::__construct($argumentsMessage[0] ?? '');
        $this->statusCode = $statusCode;
        $this->argumentsMessage = $argumentsMessage;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function getSimpleError(): SimpleError
    {
        return new SimpleError($this->message, $this->statusCode);
    }

    /**
     * @return string[]
     */
    public function getArgumentsMessage(): array
    {
        return $this->argumentsMessage;
    }
}

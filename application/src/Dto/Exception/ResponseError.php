<?php
namespace App\Dto\Exception;

use App\Dto\DtoInterface;

class ResponseError implements DtoInterface
{
    protected string $title = '';

    protected string $type = '';

    protected int $status = 0;

    protected $detail = null;

    protected ?string $reason = null;

    protected ?string $message = null;

    protected ?array $invalidParams = null;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getDetail()
    {
        return $this->detail;
    }

    public function setDetail($detail): void
    {
        $this->detail = $detail;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): void
    {
        $this->reason = $reason;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    public function getInvalidParams(): ?array
    {
        return $this->invalidParams;
    }

    public function setInvalidParams(?array $invalidParams): void
    {
        $this->invalidParams = $invalidParams;
    }
}

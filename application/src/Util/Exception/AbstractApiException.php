<?php

namespace App\Util\Exception;

use App\Dto\Exception\ResponseError;
use Exception;

abstract class AbstractApiException extends Exception
{
    abstract public function getStatusCode();

    abstract public function getTitle(): ?string;

    abstract public function getType(): ?string;

    abstract public function getDetail();

    abstract public function getReason(): ?string;

    abstract public function getInvalidParams(): ?array;

    public function getResponseDTO(): ResponseError
    {
        $responseDTO = new ResponseError();
        $responseDTO->setStatus($this->getStatusCode());
        $responseDTO->setMessage($this->getMessage());
        $responseDTO->setTitle($this->getTitle());
        $responseDTO->setType($this->getType());
        $responseDTO->setDetail($this->getDetail());
        $responseDTO->setReason($this->getReason());
        $responseDTO->setInvalidParams($this->getInvalidParams());

        return $responseDTO;
    }
}

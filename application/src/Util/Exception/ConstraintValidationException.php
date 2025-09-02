<?php

namespace App\Util\Exception;

use App\Dto\Exception\ConstraintErrorMessage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintValidationException extends AbstractApiException
{
    private const DEFAULT_ERROR_TYPE = 'about:blank';
    private const DEFAULT_ERROR_TITLE = 'Bad Request';
    private const DEFAULT_ERROR_DETAIL = 'Form Validation';

    private array $errors = [];
    private string $title;
    private string $type;
    private string $detail;
    private int $statusCode;

    public function __construct(
        $errors,
        ?int $statusCode = null,
        ?string $title = null,
        ?string $type = null,
        ?string $detail = null
    ) {
        parent::__construct($message = 'error.form.invalid.check_errors', $code = 0, $previous = null);

        $this->setErrors($errors);
        $this->statusCode = $statusCode ?? Response::HTTP_BAD_REQUEST;
        $this->title = $title ?? self::DEFAULT_ERROR_TITLE;
        $this->type = $type ?? self::DEFAULT_ERROR_TYPE;
        $this->detail = $detail ?? self::DEFAULT_ERROR_DETAIL;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array|ConstraintViolationListInterface $errors
     * @return ConstraintValidationException
     */
    public function setErrors($errors): ConstraintValidationException
    {
        if ($errors instanceof ConstraintViolationListInterface) {
            foreach ($errors->getIterator() as $error) {
                $this->errors[$error->getPropertyPath()] = $error->getMessage();
            }
        } elseif (is_array($errors) || is_iterable($errors)) {
            foreach ($errors as $field => $error) {
                $this->errors[$field] = $error;
            }
        } else {
            $this->errors['unknown'] = $errors;
        }
        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDetail()
    {
        return $this->detail;
    }

    public function getInvalidParams(): ?array
    {
        $invalidParams = [];

        foreach ($this->getErrors() as $field => $values) {
            $errorMessage = new ConstraintErrorMessage();
            if ($field) {
                $field = $this->getErrorName($field);
                $errorMessage->setName($field);
            }
            $errorMessage->setErrorMessages((array)$values);
            array_push($invalidParams, $errorMessage);
        }

        return $invalidParams;
    }

    public function getReason(): ?string
    {
        return null;
    }

    private function getErrorName(string $name): string
    {
        $str = preg_replace('#\[((\d|\w)+)\]#', '.$1', $name);
        return $str ?: $name;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}

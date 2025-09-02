<?php
declare(strict_types=1);

namespace App\Util;

use App\Dto\DtoInterface;
use App\Util\Exception\ConstraintValidationException;
use App\Util\Exception\SimpleException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsTargetedValueResolver('dto')]
class DtoArgumentResolver implements ValueResolverInterface
{
    public function __construct(
        private Serializer $serializer,
        private ValidatorInterface $validator
    ) {
    }

    /**
     * @throws ConstraintValidationException
     * @throws SimpleException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $className = $argument->getType();
        if (
            !$className
            || !is_subclass_of($className, DtoInterface::class, true)
        ) {
            return [];
        }

        $jsonString = $request->getContent();
        if (empty($jsonString) || !json_decode($jsonString)) {
            throw new SimpleException(400, 'invalid json data');
        }

        $dto = $this->serializer->deserialize($jsonString, $className);
        if (!$dto) {
            return [];
        }

        $this->validate($dto);

        // create and return the value object
        return [$argument->getName() => $dto];
    }

    public function validate($data, array $validationGroups = []): void
    {
        /** @var ConstraintViolationListInterface[] $violations */
        $violations = $this->validator->validate($data, null, $validationGroups);

        if (count($violations) > 0) {
            $errors = [];

            /** @var ConstraintViolationInterface $violation */
            foreach ($violations as $violation) {
                $key = $violation->getPropertyPath();
                if (!array_key_exists($key, $errors)) {
                    $errors[$key] = [];
                }
                array_push($errors[$key], $violation->getMessage());
            }

            throw new ConstraintValidationException($errors);
        }
    }
}

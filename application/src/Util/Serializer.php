<?php

namespace App\Util;

use App\Dto\DtoInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class Serializer
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serialize(DtoInterface $object, array $context = []): string
    {
        return $this->serializer->serialize($object, 'json', $context);
    }

    public function toArray(DtoInterface $object, $context = []): array
    {
        $data = $this->serializer->serialize($object, 'json', $context);
        $array = json_decode($data, true);
        return is_array($array) ? $array : [];
    }

    public function deserialize(?string $serializedString, string $className): ?object
    {
        if (!$serializedString) {
            return null;
        }
        return $this->serializer->deserialize($serializedString, $className, 'json');
    }
}

<?php

namespace App\Handler;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractHandler 
{
    protected RequestStack $requestStack;

    protected SerializerInterface $serializer;

    public function __construct(RequestStack $requestStack, SerializerInterface $serializer)
    {
        $this->requestStack = $requestStack;
        $this->serializer = $serializer;
    }

    abstract public function handle(?int $id = null): Response;

    protected function toJson(mixed $data, array $context = []): string
    {
        return $this->serializer->serialize($data, 'json', array_merge([
            DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s',
            'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
        ], $context));
    }

    protected function fromJson(string $json, string $type): mixed
    {
        return $this->serializer->deserialize($json, $type, 'json', [
            DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s',
        ]);
    }
}

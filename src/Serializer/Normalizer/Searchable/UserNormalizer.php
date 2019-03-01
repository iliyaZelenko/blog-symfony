<?php

namespace App\Serializer\Normalizer\Searchable;

use Algolia\SearchBundle\Searchable;
use App\Entity\User;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserNormalizer implements NormalizerInterface
{
    /**
     * @var ObjectNormalizer
     */
    private $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * Normalize a user into a set of arrays/scalars.
     *
     * @param User $object
     * @param null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        // TODO конкретные поля
        return $this->normalizer->normalize($object);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof User && Searchable::NORMALIZATION_FORMAT === $format;
    }
}

<?php

namespace App\Serializer\Normalizer\REST;

use App\Entity\UserInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserRESTNormalizer implements NormalizerInterface
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
     * @param UserInterface $object
     * @param null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        // TODO отдает пароль, правда он всеравно хешированный
        return $this->normalizer->normalize($object);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof UserInterface;
    }
}

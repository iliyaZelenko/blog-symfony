<?php

namespace App\Serializer\Normalizer\Searchable;

use Algolia\SearchBundle\Searchable;
use App\Entity\Comment;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CommentNormalizer implements NormalizerInterface
{
    /**
     * Normalize a user into a set of arrays/scalars.
     * @param Comment $object
     * @param null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        return [
            'id' => $object->getId(),
            'text' => $object->getText()
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Comment && Searchable::NORMALIZATION_FORMAT === $format;
    }
}

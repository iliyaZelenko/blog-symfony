<?php

namespace App\Serializer\Normalizer\Searchable;

use Algolia\SearchBundle\Searchable;
use App\Entity\Post;
use App\Entity\Tag;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PostNormalizer implements NormalizerInterface
{
    /**
     * @var UserNormalizer
     */
    private $userNormalizer;

    public function __construct(UserNormalizer $userNormalizer)
    {
        $this->userNormalizer = $userNormalizer;
    }

    /**
     * Normalize a user into a set of arrays/scalars.
     * @param Post $object
     * @param null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        return [
            'id' => $object->getId(),
            'title' => $object->getTitle(),
            'slug' => $object->getSlug(),
            'content' => $object->getText(),
            'contentShort' => $object->getTextShort(),
            // 'comment_count' => $object->getComments()->count(),
            'tags' => array_map(function (Tag $tag) {
                return [
                    'id' => $tag->getId(),
                    'name' => $tag->getName()
                ];
            }, $object->getTags()->toArray()),
            'createdAt' => $object->getCreatedAt()->getTimestamp(),
            // Reuse the $serializer
            'author' => $this->userNormalizer->normalize($object->getAuthor(), $format, $context)
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Post && Searchable::NORMALIZATION_FORMAT === $format;
    }
}

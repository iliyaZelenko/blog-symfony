<?php

namespace App\RESTResources\User;

use App\Entity\UserInterface;
use App\Serializer\Normalizer\REST\UserRESTNormalizer;

class UserResource
{
    /**
     * @var UserRESTNormalizer
     */
    private $normalizer;

    public function __construct(UserRESTNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function toArray(UserInterface $user): array
    {
        return $this->normalizer->normalize($user);
    }
}

<?php

namespace App\Entity\Factories;

use App\Entity\PostVote;
use App\Form\DataObjects\PostVote\PostVoteCreationData;

/**
 * Factory method pattern
 */
interface PostVoteFactoryInterface
{
    public function createNew(PostVoteCreationData $data): PostVote;
}

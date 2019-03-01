<?php

namespace App\Entity\Factories;

use App\Entity\PostVote;
use App\Form\DataObjects\PostVote\PostVoteCreationData;
use App\Repository\CommentRepositoryInterface;
use App\Repository\PostVoteRepositoryInterface;

/**
 * Factory method pattern
 */
class PostVoteFactory implements PostVoteFactoryInterface
{
    /**
     * @param PostVoteCreationData $data
     * @return PostVote
     */
    public function createNew(PostVoteCreationData $data): PostVote
    {
        $newVote = new PostVote(
            $data->getUser(),
            $data->getPost(),
            $data->getVoteValue()
        );

        return $newVote;
    }
}

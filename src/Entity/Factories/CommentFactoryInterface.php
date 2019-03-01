<?php

namespace App\Entity\Factories;

use App\Entity\Comment;
use App\Form\DataObjects\Comment\CommentCreationData;

/**
 * Factory method pattern
 */
interface CommentFactoryInterface
{
    public function createNew(CommentCreationData $data): Comment;
}

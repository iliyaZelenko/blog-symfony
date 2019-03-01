<?php

namespace App\Entity\Factories;

use App\Entity\Comment;
use App\Exceptions\AppException;
use App\Form\DataObjects\Comment\CommentCreationData;
use App\Repository\CommentRepositoryInterface;

/**
 * Factory method pattern
 */
class CommentFactory implements CommentFactoryInterface
{
    /**
     * @var CommentRepositoryInterface
     */
    private $repo;

    public function __construct(
        CommentRepositoryInterface $repo
    )
    {
        // TODO интересно нужно ли связывать репозиторий с фабриков или лучше передавать данные сразу в фабрику,
        // чтоыб не получать их в этом слое
        $this->repo = $repo;
    }

    /**
     * @param CommentCreationData $data
     * @return Comment
     * @throws AppException
     */
    public function createNew(CommentCreationData $data): Comment
    {
        $text = $data->getText();
        $parentCommentId = $data->getParentCommentId();
        $comment = new Comment(
            $data->getUser(),
            $data->getPost()
        );

        if ($parentCommentId) {
            // TODO правильно тут репозиторий использовать или может лучше в контроллере, например?
            /** @var Comment $parentComment */
            if (!$parentComment = $this->repo->find($parentCommentId)) {
                throw new AppException('Parent comment not found.', 404);
            }

            $comment->setParent($parentComment);
        }

        $comment->setText($text);

        return $comment;
    }
}

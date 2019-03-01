<?php

namespace App\Repository;

use App\Entity\Comment;
use Knp\Component\Pager\Pagination\PaginationInterface as PaginationInterfaceReturn;

interface CommentRepositoryInterface extends BaseRepositroyInterface
{
    /**
     * Возвращает первый комментарий.
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @return Comment|null
     */
    public function getFirst(): ?Comment;

    /**
     * Возвращает все пагинированные комменты по id поста.
     *
     * @param int $postId
     * @param int $page
     * @param int $perPage
     * @return PaginationInterfaceReturn
     */
    public function getPaginatedByPostId(int $postId, int $page, int $perPage): PaginationInterfaceReturn;
}

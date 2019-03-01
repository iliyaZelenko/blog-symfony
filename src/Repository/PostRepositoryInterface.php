<?php

namespace App\Repository;

use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface as PaginationInterfaceReturn;

interface PostRepositoryInterface extends BaseRepositroyInterface
{
    public function getPaginated($page, $perPage): PaginationInterfaceReturn;

    public function getByUserPaginated(User $user, $page, $perPage): PaginationInterfaceReturn;
}

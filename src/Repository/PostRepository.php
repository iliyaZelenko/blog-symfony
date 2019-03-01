<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Knp\Component\Pager\Pagination\PaginationInterface as PaginationInterfaceReturn;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository implements PostRepositoryInterface
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(RegistryInterface $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Post::class);

        $this->paginator = $paginator;
    }

    public function getPaginated($page, $perPage): PaginationInterfaceReturn
    {
        $query = $this
            ->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
        ;

        // Возвращается экземпляр: https://github.com/KnpLabs/KnpPaginatorBundle/blob/master/Pagination/SlidingPagination.php
        return $this->paginator->paginate(
            $query,
            $page,
            $perPage
        );
    }

    public function getByUserPaginated(User $user, $page, $perPage): PaginationInterfaceReturn
    {
        $query = $this
            ->createQueryBuilder('p')
            ->andWhere('p.author = :user')
            ->setParameter('user', $user)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
        ;

        return $this->paginator->paginate(
            $query,
            $page,
            $perPage
        );
    }
}

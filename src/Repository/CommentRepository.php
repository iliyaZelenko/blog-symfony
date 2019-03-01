<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Knp\Component\Pager\Pagination\PaginationInterface as PaginationInterfaceReturn;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository implements CommentRepositoryInterface
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(RegistryInterface $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Comment::class);

        $this->paginator = $paginator;
    }

    /**
     * @inheritdoc
     */
    public function getFirst(): ?Comment
    {
        return $this
            ->createQueryBuilder('comment')
            ->setMaxResults(1)
            ->orderBy('comment.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @inheritdoc
     */
    public function getPaginatedByPostId(int $postId, int $page, int $perPage): PaginationInterfaceReturn
    {
        // TODO оптимизировать, чтобы заранее получало коммент с автором (чтобы не не делать запрос автора в twig)
        $query = $this->createQueryBuilder('comment')
            ->andWhere('comment.post = :post_id')
            ->andWhere('comment.parent is NULL')
            ->setParameters([
                'post_id' => $postId,
            ])
            ->orderBy('comment.id', 'DESC')
            ->getQuery()
        ;

        // Возвращается экземпляр: https://github.com/KnpLabs/KnpPaginatorBundle/blob/master/Pagination/SlidingPagination.php
        return $this->paginator->paginate(
            $query,
            $page,
            $perPage
        );
    }
}

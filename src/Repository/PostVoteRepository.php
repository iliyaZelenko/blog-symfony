<?php

namespace App\Repository;

use App\Entity\PostVote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PostVote|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostVote|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostVote[]    findAll()
 * @method PostVote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostVoteRepository extends ServiceEntityRepository implements PostVoteRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PostVote::class);
    }
}

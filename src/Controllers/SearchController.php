<?php

namespace App\Controllers;

use Algolia\SearchBundle\IndexManagerInterface;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends AbstractController
{
    public function postSearch(EntityManagerInterface $entityManager, IndexManagerInterface $indexingManager): Response
    {
        $posts = $indexingManager->search('5', Post::class, $entityManager);

        return $this->render('blog/search/index.html.twig', [
            'posts' => $posts
        ]);
    }
}

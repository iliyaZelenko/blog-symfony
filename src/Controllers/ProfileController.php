<?php

namespace App\Controllers;

use App\Repository\PostRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends AbstractController
{
    public const POSTS_PER_PAGE = 3;

    public function index(): Response
    {
        // TODO не уверен нужно ли это писать, если писал - { path: ^/profile, roles: IS_AUTHENTICATED_FULLY }
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('user/profile/index.html.twig');
    }

    public function posts(
        PostRepositoryInterface $postRepository,
        $page = 1
    ): Response
    {
        $perPage = static::POSTS_PER_PAGE;
        $user = $this->getUser();
        $posts = $postRepository->getByUserPaginated($user, 1, $perPage);

        return $this->render('user/profile/posts.html.twig', [
            'posts' => $posts,
            'pagesCount' => $posts->getPageCount(),
            'totalPosts' => $posts->getTotalItemCount(),
            'perPage' => $perPage,
            'vue_data' => [
                'currentPage' => $page,
                'basePathURL' => '"/profile/posts/"'
            ]
        ]);
    }
}

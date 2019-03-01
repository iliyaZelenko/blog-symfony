<?php

namespace App\Controllers;

use App\Entity\Factories\CommentFactoryInterface;
use App\Entity\Factories\PostVoteFactoryInterface;
use App\Entity\Post;
use App\EventListeners\Events\CommentCreatedEvent;
use App\Exceptions\AppException;
use App\Form\DataObjects\Comment\CommentCreationData;
use App\Form\DataObjects\PostVote\PostVoteCreationData;
use App\Repository\CommentRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use App\Repository\PostVoteRepositoryInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostsController extends AbstractController
{
    public const POSTS_PER_PAGE = 3;
    public const COMMENTS_PER_PAGE = 6;

    /**
     * Get all paginated posts
     *
     * @param Request $request
     * @param PostRepositoryInterface $repo
     * @param int $page
     * @return Response
     */
    public function allPosts(
        Request $request,
        PostRepositoryInterface $repo,
        $page = 1
    ): Response
    {
        $perPageFromRequest = $request->get('perPage');
        $perPage = $perPageFromRequest ?? static::POSTS_PER_PAGE;
        $posts = $repo->getPaginated($page, $perPage);

        return $this->render('blog/posts/all_posts.html.twig', [
            'posts' => $posts,
            'pagesCount' => $posts->getPageCount(),
            'totalPosts' => $posts->getTotalItemCount(),
            'perPage' => $perPage,
            'vue_data' => [
                'currentPage' => $page,
                // TODO generate path on frontend
                'basePathURL' => '"/blog/"'
            ]
        ]);
    }

    /*
     ParamConverter:
     1) если использовать параметр "Post $post" без анотации ParamConverter,
        то Symfony сама будет использовать ParamConverter
     2) изначально ParamConverter ищет по id, чтобы осуществлялся поиск по другим полям, их нужно добавить в mapping.
     3) без mapping поиск будет только по id, будет делатся 2 запроса где WHERE id (на примере 16) будет "16" и 16
        Скрин: https://i.imgur.com/0VtcTDc.png
        С mapping со slug будет делатся 2 запроса: WHERE slug "post-title-1" и WHERE id 16
        Скрин: https://i.imgur.com/cYjSI2f.png
    */

    /**
     * @ParamConverter("post", options={"mapping" = {"slug" = "slug"}})
     * @param Request $request
     * @param ObjectManager $manager
     * @param Post $post
     * @param CommentRepositoryInterface $repoComment
     * @param $slug
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function post(
        Request $request,
        ObjectManager $manager,
        Post $post,
        CommentRepositoryInterface $repoComment,
        $slug,
        $id
    ): Response
    {
        // если slug из url не совпадает со slug в посте, то редирект на слуг поста
        // таким образом: если была ссылка с slug который поменялся (вместе с title), то такая ссылка будет работать
        // изначально ParamConverter ищет по id и (или) slug, если нашло по id, а slug отличается, то будет редирект
        if ($slug !== $post->getSlug()) {
            return $this->redirect(
                $this->generateUrl('post', [
                    'id' => $post->getId(),
                    'slug' => $post->getSlug()
                ]),
                301
            );
        }

        /* Old comment creation:
        $authUser = $this->getUser();
        $comment = new Comment($authUser, $post);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);
            $manager->flush();

            // если ASC: $rootComments[] = $comment;
            // array_unshift($rootComments, $comment);
//            $rootComments->add($comment);

            $this->addFlash(
                'success',
                'Comment saved!'
            );
        }
        */

        // $repoComment->getCommentsByPostId($id);
        $rootComments = $repoComment->getPaginatedByPostId(
            $id,
            // берется из query ?page=2 (так сделано на stackoverflow)
            $request->query->getInt('page', 1),
            static::COMMENTS_PER_PAGE
        );

        return $this->render('blog/posts/post.html.twig', [
            'post' => $post,
            'rootComments' => $rootComments,
//            'form' => $form->createView(),
            'vue_data' => [
                'showFormAddRootComment' => false,
                'formComment' => json_encode([
                    'text' => 'Form text',
                ], JSON_FORCE_OBJECT),
            ]
        ]);
    }

    /**
     * AJAX POST for creating a comment.
     *
     * @ParamConverter("post", options={"mapping" = {"slug" = "slug"}})
     * @param Request $request
     * @param Post $post
     * @param ValidatorInterface $validator
     * @param CommentFactoryInterface $commentFactory
     * @param $slug
     * @param $id
     * @return JsonResponse
     */
    public function createComment(
        Request $request,
        Post $post,
        ValidatorInterface $validator,
        CommentFactoryInterface $commentFactory,
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface $entityManager,
        $slug,
        $id
    ): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $requestData = json_decode($request->getContent(), true);
        [
            'message' => $message,
            'parentCommentId' => $parentCommentId
        ] = $requestData;
        $commentData = (new CommentCreationData($user, $post))
            ->setText($message)
            ->setParentCommentId($parentCommentId)
        ;
        $errors = $validator->validate($commentData);

        if (count($errors)) {
            return new JsonResponse([
                'error' => $errors->get(0)->getMessage()
            ]);
        }

        try {
            $comment = $commentFactory->createNew($commentData);
        } catch (AppException $e) {
            $errCode = $e->getCode();
            $errMsg = $e->getMessage();

            if ($errCode === 404) {
                throw new NotFoundHttpException($errMsg);
            }
        }

        $entityManager->persist($comment);
        $entityManager->flush();

        $eventDispatcher->dispatch(
            'comment.created',
            new CommentCreatedEvent($comment)
        );

        // TODO JsonResponseBuilder
        return new JsonResponse([
            'successMessage' => 'Comment saved!'
        ]);
    }

    /**
     * @ParamConverter("post", options={"mapping" = {"id" = "id"}})
     * @param Request $request
     * @param Post $post
     * @param PostVoteRepositoryInterface $postVoteRepo
     * @param PostVoteFactoryInterface $postVoteFactory
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function postDoVote(
        Request $request,
        Post $post,
        PostVoteRepositoryInterface $postVoteRepo,
        PostVoteFactoryInterface $postVoteFactory,
        EntityManagerInterface $entityManager
    ): RedirectResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $requestVoteValue = +$request->get('voteValue');
        $redirectResponse = $this->redirectToRoute('post', [
            'id' => $post->getId(),
            'slug' => $post->getSlug()
        ]);

        if ($userVote = $post->getUserVote($user)) {
            if ($userVote->getValue() === $requestVoteValue) {
                $post->removeVote($userVote);

                $entityManager->remove($userVote);
            } else {
                $userVote->setValue($requestVoteValue);
            }
        } else {
            $newData = new PostVoteCreationData($user, $post, $requestVoteValue);

            $postVote = $postVoteFactory->createNew($newData);

            $entityManager->persist($postVote);
        }

        $entityManager->flush();


        return $redirectResponse;
    }

    public function createPost(
        Request $request,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        $eventDispatcher->dispatch('post.created');

//        $form = $this->createForm(PostCreationFormType::class, $registrationData);
//
//        if ($user = $formHandler->handle($form, $request)) {
//            // do anything else you need here, like send an email
//
//            return $guardHandler->authenticateUserAndHandleSuccess(
//                $user,
//                $request,
//                $authenticator,
//                'main' // firewall name in security.yaml
//            );
//        }

        return $this->render('blog/posts/post_creation.html.twig');
    }
}

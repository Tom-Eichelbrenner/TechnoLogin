<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\Type\CommentType;
use App\Service\ArticleService;
use App\Service\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article/{slug}', name: 'article_show')]
    public function show(?Article $article, CommentService $commentService): Response
    {
        if (!$article) {
            return $this->redirectToRoute('app_home');
        }

        $comment = new Comment($article);

        $commentForm = $this->createForm(CommentType::class, $comment);

        return $this->renderForm('article/show.html.twig', [
            'article' => $article,
            'comment_form' => $commentForm,
            'comments' => $commentService->getPaginatedComments($article),

        ]);
    }

    #[Route('/ajax/like/{id}', name: 'article_like')]
    public function add(Article $article, ArticleService $articleService): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->json([
                'code' => 'USER_NOT_AUTHENTICATED_FULLY',
            ], Response::HTTP_UNAUTHORIZED);
        }
        $message = $articleService->toggleLike($article, $user);
        $likeCount = $articleService->getLikeCount($article);

        return $this->json([
            'code' => 'LIKE_ADDED_SUCCESSFULLY',
            'message' => $message,
            'count' => $likeCount,
        ], Response::HTTP_OK);
    }
}

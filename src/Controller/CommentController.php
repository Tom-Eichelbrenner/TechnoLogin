<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @method User getUser()
 */
class CommentController extends AbstractController
{
    #[Route('/ajax/comments', name: 'comment_add')]
    public function add(Request $request, ArticleRepository $articleRepository, EntityManagerInterface $entityManager): Response
    {
        $commentData = $request->request->all('comment');
        if (!$this->isCsrfTokenValid('comment_add', $commentData['_token'])) {
            return $this->json([
                'code' => 'INVALID_TOKEN',
                'message' => 'Formulaire invalide',
            ], Response::HTTP_FORBIDDEN);
        }

        $article = $articleRepository->findOneBy(['id' => $commentData['article']]);

        if (!$article) {
            return $this->json([
                'code' => 'ARTICLE_NOT_FOUND',
                'message' => 'Cet article n\'existe pas, veuillez rafraichir la page et réessayer.',
            ], Response::HTTP_NOT_FOUND);
        }

        $user = $this->getUser();

        if (!$user) {
            return $this->json([
                'code' => 'USER_NOT_AUTHENTICATED_FULLY',
                'message' => 'Vous devez être connecté pour pouvoir poster un commentaire.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $comment = new Comment($article);
        $comment->setContent($commentData['content']);
        $comment->setUser($user);
        $comment->setCreatedAt(new \DateTime());

        $entityManager->persist($comment);
        $entityManager->flush();

        $html = $this->renderView('comment/index.html.twig', [
            'comment' => $comment,
        ]);

        return $this->json([
            'code' => 'COMMENT_ADDED_SUCCESSFULLY',
            'html' => $html,
            'numberOfComments' => $article->getComments()->count(),
        ], 200);
    }
}

<?php

namespace App\Controller;

use App\Entity\Option;
use App\Entity\User;
use App\Form\Type\WelcomeType;
use App\Model\WelcomeModel;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Service\ArticleService;
use App\Service\OptionService;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ArticleService $articleService, CategoryRepository $categoryRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'articles' => $articleService->getPaginatedArticles(),
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/welcome', name: 'app_welcome')]
    public function welcome(Request                     $request,
                            EntityManagerInterface      $entityManager,
                            UserPasswordHasherInterface $hasher,
                            OptionService               $optionService
    ): Response
    {


        $welcomeForm = $this->createForm(WelcomeType::class, new WelcomeModel());

        $welcomeForm->handleRequest($request);
        if ($welcomeForm->isSubmitted() && $welcomeForm->isValid()) {
            /** @var WelcomeModel $data */
            $data = $welcomeForm->getData();

            $siteTitle = new Option(WelcomeModel::SITE_TITLE_LABEL, WelcomeModel::SITE_TITLE_NAME, $data->getSiteName(), TextType::class);
            $siteInstalled = new Option(WelcomeModel::SITE_INSTALLED_LABEL, WelcomeModel::SITE_INSTALLED_NAME, true, null);

            $user = new User($data->getUsername());
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($hasher->hashPassword($user, $data->getPassword()));

            $entityManager->persist($siteTitle);
            $entityManager->persist($siteInstalled);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }


        return $this->render('home/welcome.html.twig', [
            'welcomeForm' => $welcomeForm->createView(),
        ]);
    }
}

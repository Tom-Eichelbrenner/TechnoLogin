<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\RegistrationFormType;
use App\Form\Type\UpdateProfileType;
use App\Service\ArticleService;
use App\Service\OptionService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    public function __construct(
        private OptionService  $optionService,
        private ArticleService $articleService,
    )
    {
    }

    #[Route('/user/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $userCanRegister = $this->optionService->getValue('blog_allow_registration');
        if (!$userCanRegister) {
            return $this->redirectToRoute('app_home');
        }
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_home');
        }

        return $this->render('user/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route(path: '/user/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/user/{username}', name: 'user_profile')]
    public function index(?User $user): Response
    {
        if (!$user) {
            return $this->redirectToRoute('app_home');
        }
        $form = $this->createForm(UpdateProfileType::class, $user);
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/ajax/profile', name: 'user_profile_update')]
    public function update(Request $request, EntityManagerInterface $entityManager, UserService $userService): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->json([
                'code' => 'USER_NOT_AUTHENTICATED_FULLY',
                'message' => 'Vous devez être authentifié pour modifier votre profil',
            ], Response::HTTP_UNAUTHORIZED);
        }
        $form = $this->createForm(UpdateProfileType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($userService->getUserByUsername($form->get('username')->getData())) {
                if ($user !== $userService->getUserByUsername($form->get('username')->getData())) {
                    return $this->json([
                        'code' => 'USERNAME_ALREADY_EXISTS',
                        'message' => 'Ce nom d\'utilisateur est déjà utilisé',
                    ], Response::HTTP_BAD_REQUEST);
                }
            }
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->json([
                'code' => 'PROFILE_UPDATED_SUCCESSFULLY',
                'message' => 'Votre profil a été mis à jour avec succès',
                'username' => $user->getUsername(),
                'about' => $user->getAbout(),
            ], Response::HTTP_OK);
        }
        return $this->json([
            'code' => 'PROFILE_UPDATE_FAILED',
            'message' => 'Une erreur est survenue lors de la mise à jour de votre profil',
        ], Response::HTTP_BAD_REQUEST);
    }
}

<?php

namespace App\Controller;

use App\Entity\Duck;
use App\Form\DuckType;
use App\Form\RegistrationFormType;
use App\Repository\DuckRepository;
use App\Repository\QuackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class RegistrationController extends AbstractController
{

    #[Route('/register', name: 'app_register',methods: ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        dump($request);
        $user = new Duck();
        dump($user);
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();


            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_quack_index');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('register/duck', name: 'app_duck_show', methods: ['GET'])]
    public function show(): Response
    {
        $user=$this->getUser();
        if (!$user instanceof Duck) {
            throw $this->createAccessDeniedException('L\'utilisateur connecté est invalide.');
        }
        return $this->render('registration/dashboard.html.twig', [
            'duck' => $user,
        ]);
    }

    #[Route('duck/{quackId}', name: 'app_duck_profile', methods: ['GET'])]
    public function showDuck(int $quackId, QuackRepository $quackRepository): Response
    {
        $quack = $quackRepository->find($quackId);
        $duck = $quack->getAuthor();

        return $this->render('registration/profile.html.twig', [
            'duck' => $duck,
        ]);
    }


    #[Route('register/duck/edit', name: 'app_duck_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user=$this->getUser();
        if (!$user instanceof Duck) {
            throw $this->createAccessDeniedException('Accès interdit.');
        }

        $form = $this->createForm(DuckType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_quack_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('registration/edit_duck.html.twig', [
            'duck' => $user,
            'form' => $form,
        ]);
    }
    #[Route('register/duck/delete', name: 'app_duck_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $entityManager, TokenStorageInterface$tokenStorage, SessionInterface $session, ): Response
    {
        $user=$this->getUser();
        if (!$user instanceof Duck) {
            throw $this->createAccessDeniedException('Accès interdit.');
        }
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $tokenStorage->setToken(null);
            $session->invalidate();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_quack_index', [], Response::HTTP_SEE_OTHER);
    }


}

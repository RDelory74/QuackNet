<?php

namespace App\Controller;

use App\Entity\Quack;
use App\Entity\Tag;
use App\Form\QuackType;
use App\Form\SearchType;
use App\Repository\DuckRepository;
use App\Repository\QuackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
final class QuackController extends AbstractController
{
    #[Route('/',name: 'app_quack_index', methods: ['GET'])]
    public function index(QuackRepository $quackRepository, Request $request): Response
    {
        $form = $this ->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $quackRepository -> searchVisibleQuacks($data);
        }
        return $this->render('quack/index.html.twig', [
            'form' => $form->createView(),
            'quacks' => $quackRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_quack_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $quack = new Quack();
        $form = $this->createForm(QuackType::class, $quack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quack->setAuthor($user);

            $pictureFile = $form->get('picture')->getData();
            if ($pictureFile) {
                $newFilename = uniqid() . '.' . $pictureFile->guessExtension();
                $pictureFile->move(
                    $this->getParameter('uploads_directory'),
                    $newFilename
                );
                $quack->setPicture($newFilename);
            }

            $tagsData = $form->get('tags')->getData();
            if (count($tagsData) > 6) {
                $this->addFlash('error', 'You can only Kwak 6 Tags');
                return $this->render('quack/new.html.twig', [
                    'quack' => $quack,
                    'form' => $form->createView(),
                ]);
            }

            foreach ($tagsData as $tagData) {
                $tag = $entityManager->getRepository(Tag::class)->findOneBy(['word' => $tagData]);
                if (!$tag) {
                    $tag = new Tag();
                    $tag->setWord($tagData);
                    $entityManager->persist($tag);
                }
                $quack->addTag($tag);
            }

            $entityManager->persist($quack);
            $entityManager->flush();

            return $this->redirectToRoute('app_quack_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quack/new.html.twig', [
            'quack' => $quack,
            'form' => $form,
        ]);
    }

    #[Route('quack/{id}', name: 'app_quack_show', methods: ['GET'])]
    public function show(Quack $quack): Response
    {
        return $this->render('quack/show.html.twig', [
            'quack' => $quack,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_quack_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quack $quack, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuackType::class, $quack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //picture
            $pictureFile = $form->get('picture')->getData();
            if ($pictureFile) {
                $newFilename = uniqid() . '.' . $pictureFile->guessExtension();
                $pictureFile->move(
                    $this->getParameter('uploads_directory'),
                    $newFilename
                );
                $quack->setPicture($newFilename);
            }

            //tag
            $tagsData = $form->get('tags')->getData();
            if (count($tagsData) > 6) {
                $this->addFlash('error', 'Vous ne pouvez ajouter que 6 tags maximum.');
                return $this->render('quack/edit.html.twig', [
                    'quack' => $quack,
                    'form' => $form->createView(),
                ]);
            }

            foreach ($tagsData as $tagData) {
                $tag = $entityManager->getRepository(Tag::class)->findOneBy(['word' => $tagData]);
                if (!$tag) {
                    $tag = new Tag();
                    $tag->setWord($tagData);
                    $entityManager->persist($tag);
                }
                $quack->addTag($tag);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_quack_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quack/edit.html.twig', [
            'quack' => $quack,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_quack_delete', methods: ['POST'])]
    public function delete(Request $request, Quack $quack, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quack->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($quack);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_quack_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/quack/duck/{authorId}', name: 'app_quack_by_duck', methods: ['GET', 'POST'])]
    public function showCoinCoinsByQuack(int $authorId, QuackRepository $quackRepository, DuckRepository $duckRepository): Response
    {
        $duck = $duckRepository->find($authorId);

        $quack = $quackRepository->findBy(['auhtor' => $authorId]);

        return $this->render('coincoin/index.html.twig', [
            'quacks' => $quack,
            'duck' => $duck
        ]);
    }
}

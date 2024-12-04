<?php

namespace App\Controller;

use App\Entity\Coincoin;
use App\Form\CoincoinType;
use App\Repository\CoincoinRepository;
use App\Repository\QuackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/coincoin')]
final class CoincoinController extends AbstractController
{
    #[Route(name: 'app_coincoin_index', methods: ['GET'])]
    public function index(CoincoinRepository $coincoinRepository): Response
    {
        return $this->render('coincoin/index.html.twig', [
            'coincoins' => $coincoinRepository->findAll(),
        ]);
    }

    #[Route('/coincoin/{quackId}/new', name: 'app_coincoin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $quackId, QuackRepository $quackRepository): Response
    {
        $user = $this->getUser();
        $quack = $quackRepository->find($quackId);

        if (!$quack) {
            throw $this->createNotFoundException('Quack not found');
        }

        $coincoin = new Coincoin();
        $form = $this->createForm(CoincoinType::class, $coincoin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coincoin->setParentId($quack);
            $coincoin->setAuthor($user);
            $entityManager->persist($coincoin);
            $entityManager->flush();

            return $this->redirectToRoute('app_coincoin_by_quack', ['quackId'=>$quackId], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coincoin/new.html.twig', [
            'coincoin' => $coincoin,
            'form' => $form,
            'quackId' => $quackId,
            'quack' => $quack
        ]);
    }

    #[Route('/{id}', name: 'app_coincoin_show', methods: ['GET'])]
    public function show(Coincoin $coincoin): Response
    {
        return $this->render('coincoin/show.html.twig', [
            'coincoin' => $coincoin,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_coincoin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Coincoin $coincoin, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CoincoinType::class, $coincoin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_coincoin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coincoin/edit.html.twig', [
            'coincoin' => $coincoin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_coincoin_delete', methods: ['POST'])]
    public function delete(Request $request, Coincoin $coincoin, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coincoin->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($coincoin);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_coincoin_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/coincoin/quack/{quackId}', name: 'app_coincoin_by_quack', methods: ['GET', 'POST'])]
    public function showCoinCoinsByQuack(int $quackId, CoinCoinRepository $coinCoinRepository, QuackRepository $quackRepository): Response
    {
        $quack = $quackRepository->find($quackId);

        $coincoins = $coinCoinRepository->findBy(['parentId' => $quackId]);

        return $this->render('coincoin/index.html.twig', [
            'coincoins' => $coincoins,
            'quackId' => $quackId,
            'quack' => $quack
        ]);
    }

}

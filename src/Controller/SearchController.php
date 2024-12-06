<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\QuackRepository;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function index(QuackRepository  $quackRepository, Request $request): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $quackRepository -> searchVisibleQuacks($data);
        }

        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
        ]);
    }
}

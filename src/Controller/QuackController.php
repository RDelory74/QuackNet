<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class QuackController extends AbstractController
{
    #[Route('/quack', name: 'app_quack')]
    public function index(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'QuackController',
        ]);
    }
    #[Route('/', name: 'app_index')]
public function home(): Response
    {
        return $this->render('quack/index.html.twig', [
            'controller_name' => 'QuackController',
        ]);
    }
}

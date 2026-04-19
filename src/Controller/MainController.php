<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')] // Change the name here to app_home
    public function index(): Response
    {
        // If the user is logged in, we can show them a "Welcome" message
        // Otherwise, we show the public landing page
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
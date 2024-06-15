<?php
// src/Controller/DefaultController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route(path: '/', name: 'homepage')]
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('app_categorias_index');
    }
}

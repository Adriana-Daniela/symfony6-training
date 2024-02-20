<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

#[AsController]
class PictureController
{
    #[Route('/picture', name: 'app_picture_index')]
    public function __invoke(Environment $twig): Response
    {
        return new Response($twig->render('picture/index.html.twig', [
            'controller_name' => 'PictureController',
        ]));
    }
}

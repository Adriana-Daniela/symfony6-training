<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HelloController extends AbstractController
{
    //#[IsGranted('ROLE_CLOWN')]
    #[Route('/hello/{name?World}', name: 'app_hello_index', requirements: ['name' => '(?:\pL|[- ])+'])]
    public function index(string $name, #[Autowire(param: 'app.symfony_version')] string $sfVersion): Response
    {
        dump($sfVersion);

        return $this->render('hello/index.html.twig', [
            'controller_name' => $name,
        ]);
    }
}

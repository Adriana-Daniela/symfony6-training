<?php

namespace App\Controller;

use App\Book\BookManager;
use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('', name: 'app_book_index', methods: ['GET'])]
    public function index(BookRepository $repository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $repository->findAll(),
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_book_show', methods: ['GET'])]
    public function show(int $id, BookManager $manager): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $manager->getOne($id),
        ]);
    }
}

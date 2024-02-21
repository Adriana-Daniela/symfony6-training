<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\User;
use App\Form\MovieType;
use App\Movie\Enum\SearchType;
use App\Movie\Provider\MovieProvider;
use App\Repository\MovieRepository;
use App\Security\Voter\MovieVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/movie')]
class MovieController extends AbstractController
{
    #[Route('', name: 'app_movie_index')]
    public function index(MovieRepository $repository): Response
    {
        return $this->render('movie/index.html.twig', [
            'movies' => $repository->findAll(),
        ]);
    }

    #[IsGranted(MovieVoter::RATED, 'movie')]
    #[Route('/{id<\d+>}', name: 'app_movie_show', methods: ['GET'])]
    public function show(?Movie $movie): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[IsGranted(MovieVoter::RATED, 'movie')]
    #[Route('/omdb/{title}', name: 'app_movie_omdb', methods: ['GET'])]
    public function omdb(#[ValueResolver('movie_title')] ?Movie $movie): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('/new', name: 'app_movie_new', methods: ['GET', 'POST'])]
    #[Route('/{id<\d+>}/edit', name: 'app_movie_edit', methods: ['GET', 'POST'])]
    public function save(Request $request, EntityManagerInterface $manager, ?Movie $movie): Response
    {
        if ($movie) {
            $this->denyAccessUnlessGranted(MovieVoter::EDIT, $movie);
        }
        $movie ??= new Movie();

        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (($user = $this->getUser()) instanceof User) {
                $movie->setCreatedBy($user);
            }
            $manager->persist($movie);
            $manager->flush();

            return $this->redirectToRoute('app_movie_show', ['id' => $movie->getId()]);
        }

        return $this->render('movie/save.html.twig', [
            'form' => $form,
            'movie' => $movie,
        ]);
    }
}

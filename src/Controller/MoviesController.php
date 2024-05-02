<?php

namespace App\Controller;

use DateTime;
use App\Entity\Movie;
use App\Entity\Rating;
use App\Entity\Comments;
use App\Entity\Categories;
use App\Form\CommentFormType;
use App\Service\MoviesService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MoviesController extends AbstractController
{
    private $em;
    private $moviesService;
    private $ur;
    public function __construct(EntityManagerInterface $em, MoviesService $moviesService, UserRepository $ur) 
    {
        $this->em = $em;
        $this->moviesService = $moviesService;
        $this->ur = $ur;
    }

    #[Route('/', name: '')]
    public function home(Request $request, PaginatorInterface $paginator)
    {
        return $this->index($request, $paginator);
    }

    #[Route('/movies', name: 'movies')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $movies = $this->em->getRepository(Movie::class)->findAll();

        $pagination = $paginator->paginate(
            $movies,
            $request->query->getInt('page', 1),
            3
        );

        return $this->render('movies/index.html.twig', [
            'movies' => $movies,
            'pagination' => $pagination,

        ]);
    }

    #[Route('/movies/search', name: 'movies_search')]
    public function search(): Response
    {
        return $this->render('movies/search.html.twig', [
            'movies' => $this->em->getRepository(Movie::class)->findAll(),
            'categoriesSelect' => $this->em->getRepository(Categories::class)->findAll()
        ]);
    }

    #[Route('/movies/{id}', methods: ['GET','POST'], name: 'show_movie')]
    public function show(int $id, Request $request): Response
    {   
        $movie = $this->em->getRepository(Movie::class)->find($id);
        $existingRating = $this->moviesService->getExistingRating($movie, $this->getUser());
        $comment = new Comments();
        $user = $this->getUser();
        if ($user) {
            $userInfo = $this->ur->find($user);
            $comment->setAuthor($userInfo->getEmail());
            $comment->setCreatedAt(new DateTime());
        }
        $commentForm = $this->createForm(CommentFormType::class, $comment);
        $commentForm->handleRequest($request);

        if($this->moviesService->show($id, $movie, $comment, $commentForm, $this->getUser()))
            $this->addFlash('success','Comment added successfully');

        return $this->render('movies/show.html.twig', [
            'movie' => $movie,
            'commentForm' => $commentForm->createView(),
            'existingRating' => $existingRating
        ]);
    }

    #[Route('/movie/rateMovie/{id}', methods: ['GET','POST'], name: 'rate_movie')]
    public function rateMovie(Request $request, int $id): Response
    {
        $ratingValue = $request->get('rating');
        $movie = $this->em->getRepository(Movie::class)->find($id);

        return $this->moviesService->rateMovie($movie, $ratingValue, $this->getUser());
    }

}

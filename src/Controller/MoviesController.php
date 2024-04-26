<?php

namespace App\Controller;

use DateTime;
use App\Entity\Movie;
use App\Entity\Comments;
use App\Entity\Rating;
use App\Form\MovieFormType;
use App\Form\CommentFormType;
use App\Repository\CategoriesRepository;
use App\Repository\UserRepository;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MoviesController extends AbstractController
{
    private $em;
    private $movieRepository;
    private $userRepository;
    private $categoriesRepository;

    public function __construct(EntityManagerInterface $em, MovieRepository $movieRepository, UserRepository $userRepository, CategoriesRepository $categoriesRepository) 
    {
        $this->em = $em;
        $this->movieRepository = $movieRepository;
        $this->userRepository = $userRepository;
        $this->categoriesRepository = $categoriesRepository;
    }
    #[Route('/', name: '')]
    public function home(Request $request, PaginatorInterface $paginator)
    {
        return $this->index($request, $paginator);
    }

    #[Route('/movies', name: 'movies')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $movies = $this->movieRepository->findAll();

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
        $movies = $this->movieRepository->findAll();
        $categories = $this->categoriesRepository->findAll();

        return $this->render('movies/search.html.twig', [
            'movies' => $movies,
            'categoriesSelect' => $categories

        ]);
    }
    #[Route('/movies/{id}', methods: ['GET','POST'], name: 'show_movie')]
    public function show(int $id, Request $request): Response
    {
        $movie = $this->movieRepository->find($id);
        $comment = new Comments();

        $user = $this->getUser();
        if ($user) {
            $userInfo = $this->userRepository->find($user);
            $comment->setAuthor($userInfo->getEmail());
            $comment->setCreatedAt(new DateTime());
        }

        $commentForm = $this->createForm(CommentFormType::class, $comment);
        $commentForm->handleRequest($request);
        
        
        $existingRating = $this->em->getRepository(Rating::class)->findOneBy(['movie' => $movie, 'user' => $user]);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setAuthor($commentForm->get('author')->getData());
            $comment->setContent($commentForm->get('content')->getData());
            $comment->setMovie($movie);
            $comment->setUser($user);

    
            $this->em->persist($comment);
            $this->em->flush();
    
            return $this->redirectToRoute('show_movie', ['id' => $id]);
        }
    
        return $this->render('movies/show.html.twig', [
            'movie' => $movie,
            'commentForm' => $commentForm->createView(),
            'existingRating' => $existingRating
        ]);
    }

    #[Route('/movie/rateMovie/{id}', methods: ['GET','POST'], name: 'rate_movie')]
    public function rateMovie(Request $request, int $id)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedException('This endpoint accepts only AJAX requests.');
        }

        $movie = $this->em->getRepository(Movie::class)->find($id);

        if (!$movie) {
            return new JsonResponse(['success' => false, 'message' => 'Film not found'], 404);
        }

        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'User not logged in'], 401);
        }
        $ratingValue = $request->request->get('rating');
        $existingRating = $this->em->getRepository(Rating::class)->findOneBy(['movie' => $movie, 'user' => $user]);
        
        if ($existingRating) {
            $existingRating->setScore($ratingValue);
        } else {
            $rating = new Rating();
            $rating->setMovie($movie);
            $rating->setScore($ratingValue);
            $rating->setUser($user);
            $this->em->persist($rating);
        }

        $this->em->flush();

        $totalRatings = count($movie->getRating());
        $sumRatings = array_reduce($movie->getRating()->toArray(), function($carry, $rating) {
            return $carry + $rating->getScore();
        }, 0);

        $averageRating = $sumRatings / $totalRatings;

        $movie->setAverageRating($averageRating);
        $this->em->flush();

        return new JsonResponse(['success' => true, 'message' => 'Film rated successfully', 'averageRating' => $averageRating]);
    }

}

<?php

namespace App\Service;

use DateTime;
use App\Entity\Movie;
use App\Entity\Rating;
use App\Entity\Comments;
use App\Form\CommentFormType;
use App\Repository\UserRepository;
use App\Repository\MovieRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MoviesService
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
    
    public function show(int $id, $movie, $comment, $commentForm, $user)
    {
        $movie = $this->movieRepository->find($id);

        if (!($commentForm->isSubmitted() && $commentForm->isValid())) {return false; }
        $comment->setAuthor($commentForm->get('author')->getData());
        $comment->setContent($commentForm->get('content')->getData());
        $comment->setMovie($movie);
        $comment->setUser($user);

        $this->em->persist($comment);
        $this->em->flush();
    }

    public function rateMovie($movie, $ratingValue, $user)
    {
        if (!$movie) {
            return new JsonResponse(['success' => false, 'message' => 'Film not found'], 404);
        }

        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'User not logged in'], 401);
        }
        
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

    public function getExistingRating(Movie $movie, $user): void
    {
        $this->em->getRepository(Rating::class)->findOneBy(['movie' => $movie, 'user' => $user]);
    }
}

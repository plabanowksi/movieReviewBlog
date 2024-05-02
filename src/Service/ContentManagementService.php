<?php

namespace App\Service;

use App\Entity\Movie;
use App\Repository\UserRepository;
use App\Repository\MovieRepository;
use App\Repository\CommentsRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ContentManagementService
{
    private $em;
    private $movieRepository;
    private $categoriesRepository;
    private $commentsRepository;
    private $params;

    public function __construct(EntityManagerInterface $em, MovieRepository $movieRepository, CategoriesRepository $categoriesRepository, CommentsRepository $commentsRepository, ParameterBagInterface $params) 
    {
        $this->em = $em;
        $this->movieRepository = $movieRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->commentsRepository = $commentsRepository;
        $this->params = $params;
    }

  public function viewMovies(): array
    {
        return $this->em->getRepository(Movie::class)->findAll();
    }
    public function createMovie(FormInterface $form)
    {
        if (!($form->isSubmitted() && $form->isValid())) {return ; }

        $newMovie  = $form->getData();
        $imagePath = $form->get('image_path')->getData();
        if($imagePath){
            $newFileName = uniqid() . '.' . $imagePath->guessExtension();
            try{
                $imagePath->move(
                    $this->params->get('kernel.project_dir') . '/public/uploads', $newFileName
                );
            }catch(FileException $e){
                // $this->addFlash('warrning', $e->getMessage());
            }

            $newMovie->setImagePath('/uploads/' . $newFileName);
        }
        $this->em->persist($newMovie);
        $this->em->flush();
        
    }

    public function editMovie(FormInterface $form, Movie $movie): void
    {
        $imagePath = $form->get('image_path')->getData();
        if (!($form->isSubmitted() && $form->isValid())) {return ; }
        if($imagePath){
            if($movie->getImagePath() !== null){
                if(file_exists($$this->params->get('kernel.project_dir') . $movie->getImagePath()) == false){
                    $this->params->get('kernel.project_dir') . $movie->getImagePath();

                    $newFileName = uniqid() . '.' . $imagePath->guessExtension();
                    try{
                        $imagePath->move(
                            $this->params->get('kernel.project_dir') . '/public/uploads', $newFileName
                        );
                    }catch(FileException $e){
                        // $this->addFlash('warrning', $e->getMessage());
                    }
    
                    $movie->setImagePath('/uploads/' . $newFileName);
                    $this->em->flush();
                }
            }
        }else{
            $movie->setTitle($form->get('title')->getData());
            $movie->setReleaseYear($form->get('release_year')->getData());
            $movie->setDescription($form->get('description')->getData());

            $this->em->flush();
        }
    }

    public function deleteMovie(int $id): void
    {
        $movie = $this->movieRepository->find($id);
        $this->em->remove($movie);
        $this->em->flush();
    }

    public function deleteComments(int $id): void
    {
        $comment = $this->commentsRepository->find($id);
        $this->em->remove($comment);
        $this->em->flush();
    }

    public function createCategorie($categorieForm)
    {
        if (!($categorieForm->isSubmitted() && $categorieForm->isValid())) {return ; }
        $categorie = $categorieForm->getData();
        $this->em->persist($categorie);
        $this->em->flush();
        
    }
    public function editCategorie(FormInterface $categorieForm, $categorie)
    {
        if (!($categorieForm->isSubmitted() && $categorieForm->isValid())) {return ; }
        $categorie->setName($categorieForm->get('name')->getData());
        $this->em->flush();
    }

    public function deleteCategorie(int $id): void
    {
        $categorie = $this->categoriesRepository->find($id);
        $this->em->remove($categorie);
        $this->em->flush();
    }
}

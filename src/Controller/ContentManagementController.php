<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Comments;
use App\Entity\Categories;
use App\Form\MovieFormType;
use App\Form\CategoriesFormType;
use App\Repository\UserRepository;
use App\Repository\MovieRepository;
use App\Repository\CommentsRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ContentManagementController extends AbstractController
{
    
    
    private $em;
    private $movieRepository;
    private $userRepository;
    private $categoriesRepository;
    private $commentsRepository;


    public function __construct(EntityManagerInterface $em, MovieRepository $movieRepository, UserRepository $userRepository, CategoriesRepository $categoriesRepository, CommentsRepository $commentsRepository) 
    {
        $this->em = $em;
        $this->movieRepository = $movieRepository;
        $this->userRepository = $userRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->commentsRepository = $commentsRepository;
    }


    #[Route('/admin/movie/view', name: 'app_content_management')]
    public function view(): Response
    {
        $movies = $this->movieRepository->findAll();

        return $this->render('adminpanel/content_management/movies.html.twig', [
            'movies' => $movies
        ]);
    }

    
    #[Route('/admin/movie/create', name: 'create_movie')]
    public function create(Request $request): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieFormType::class, $movie);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $newMovie  = $form->getData();
            $imagePath = $form->get('image_path')->getData();
            if($imagePath){
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();
                try{
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads', $newFileName
                    );
                }catch(FileException $e){
                    return new Response($e->getMessage());
                }

                $newMovie->setImagePath('/uploads/' . $newFileName);
            }
            $this->em->persist($newMovie);
            $this->em->flush();

            $this->addFlash('success','Movie created successfully');
            
            return $this->redirectToRoute('movies');
        }

        return $this->render('adminpanel/content_management/movies/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/admin/movie/edit/{id}',methods:['GET','POST'], name: 'edit_movie')]
    public function edit(int $id, Request $request): Response
    {
        $movie = $this->movieRepository->find($id);

        $form = $this->createForm(MovieFormType::class, $movie);

        $form->handleRequest($request);
        $imagePath = $form->get('image_path')->getData();
        if($form->isSubmitted() && $form->isValid()){
            if($imagePath){
                if($movie->getImagePath() !== null){
                    if(file_exists($this->getParameter('kernel.project_dir') . $movie->getImagePath()) == false){
                        $this->getParameter('kernel.project_dir') . $movie->getImagePath();

                        $newFileName = uniqid() . '.' . $imagePath->guessExtension();
                        try{
                            $imagePath->move(
                                $this->getParameter('kernel.project_dir') . '/public/uploads', $newFileName
                            );
                        }catch(FileException $e){
                            return new Response($e->getMessage());
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
            $this->addFlash('success','Movie edited successfully');
            return $this->redirectToRoute('movies');

        }
        return $this->render('adminpanel/content_management/movies/edit.html.twig',[
            'movie' => $movie,
            'form' => $form
        ]);
    }

    #[Route('/admin/movie/delete/{id}', methods: ['GET','DELETE'], name: 'delete_movie')]
    public function delete(int $id): Response
    {
        $movie = $this->movieRepository->find($id);
        $this->em->remove($movie);
        $this->em->flush();

        $this->addFlash('success','Movie deleted successfully');
        return $this->redirectToRoute('movies');
    }

    #[Route('/admin/comments/view', methods:['GET'], name: 'comments_adminpanel')]
    public function checkcomments(): Response
    {
        $reviews = $this->em->getRepository(Comments::class)->findAll();

        return $this->render('adminpanel/content_management/comments.html.twig', [
            'reviews' => $reviews
        ]);
    }

    #[Route('/admin/comments/delete/{id}', methods:['GET','DELETE'], name: 'deletecomments_adminpanel')]
    public function deletecomments(int $id): Response
    {
        $comment = $this->commentsRepository->find($id);
        $this->em->remove($comment);
        $this->em->flush();
        $this->addFlash('success','Comment deleted successfully');

        return $this->redirectToRoute('comments_adminpanel');
    }

    #[Route('/admin/categories/view', methods:['GET'], name: 'categories_adminpanel')]
    public function categoriesView(): Response
    {
        $categories = $this->em->getRepository(Categories::class)->findAll();

        return $this->render('adminpanel/content_management/categories.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/admin/categories/create', methods:['GET','POST'], name: 'categoriescreate_adminpanel')]
    public function categoriesCreate(Request $request): Response
    {
        $categorie = new Categories();
        $categorieForm = $this->createForm(CategoriesFormType::class, $categorie);

        $categorieForm->handleRequest($request);
        if($categorieForm->isSubmitted() && $categorieForm->isValid()){
            $categorie  = $categorieForm->getData();

            $this->em->persist($categorie);
            $this->em->flush();

            $this->addFlash('success','Category created successfully');
            return $this->redirectToRoute('categories_adminpanel');
        }

        return $this->render('adminpanel/content_management/categories/create.html.twig', [
            'categorieForm' => $categorieForm
        ]);
    }
    #[Route('/admin/categories/edit/{id}', methods:['GET','POST'], name: 'categoriesedit_adminpanel')]
    public function categoriesEdit(int $id, Request $request): Response
    {
        $categorie = $this->em->getRepository(Categories::class)->find($id);
        $categorieForm = $this->createForm(CategoriesFormType::class, $categorie);
        $categorieForm->handleRequest($request);

        if($categorieForm->isSubmitted() && $categorieForm->isValid()){
                $categorie->setName($categorieForm->get('name')->getData());

                $this->em->flush();
                $this->addFlash('success','Category edited successfully');
                return $this->redirectToRoute('categories_adminpanel');
        }
        return $this->render('adminpanel/content_management/categories/edit.html.twig', [
            'categorie' => $categorie,
            'categorieForm' => $categorieForm
        ]);
    }

    #[Route('/admin/categories/delete/{id}', methods: ['GET','DELETE'], name: 'delete_categories')]
    public function deleteCategorie(int $id): Response
    {
        $categorie = $this->categoriesRepository->find($id);
        $this->em->remove($categorie);
        $this->em->flush();
        
        $this->addFlash('success','Category deleted successfully');
        return $this->redirectToRoute('categories_adminpanel');
    }

}

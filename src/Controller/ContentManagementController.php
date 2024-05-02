<?php

namespace App\Controller;


use App\Entity\Movie;
use App\Entity\Comments;
use App\Entity\Categories;
use App\Form\MovieFormType;
use App\Form\CategoriesFormType;
use App\Repository\MovieRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ContentManagementService;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContentManagementController extends AbstractController
{
    private $em;
    private $cmService;
    private $categoriesRepository;
    private $movieRepository;

    public function __construct(ContentManagementService $cmService, EntityManagerInterface $em, CategoriesRepository $categoriesRepository, MovieRepository $movieRepository) 
    {
        $this->em = $em;
        $this->cmService = $cmService;
        $this->categoriesRepository = $categoriesRepository;
        $this->movieRepository = $movieRepository;
    }

    #[Route('/admin/movie/view', methods:['GET','POST'], name: 'app_content_management')]
    public function view(): Response
    {
        return $this->render('adminpanel/content_management/movies.html.twig', [
            'movies' => $this->cmService->viewMovies()
        ]);
    }

    #[Route('/admin/movie/create', methods:['GET','POST'], name: 'create_movie')]
    public function create(Request $request): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieFormType::class, $movie);
        $form->handleRequest($request);

        if($this->cmService->createMovie($form))
            $this->addFlash('success','Movie created successfully');

        return $this->render('adminpanel/content_management/movies/create.html.twig', [
            'contactForm' => $this->createForm(MovieFormType::class)->createView(),
        ]);
    }

    #[Route('/admin/movie/edit/{id}', methods:['GET','POST'], name: 'edit_movie')]
    public function edit(int $id, Request $request): Response
    {
        $movie = $this->movieRepository->find($id);
        $form = $this->createForm(MovieFormType::class, $movie);
        $form->handleRequest($request);
        if($this->cmService->editMovie($form, $movie))
            $this->addFlash('success','Movie edited successfully');

        return $this->render('adminpanel/content_management/movies/edit.html.twig',[
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    #[Route('/admin/movie/delete/{id}', methods: ['GET','DELETE'], name: 'delete_movie')]
    public function delete(int $id): Response
    {
        if($this->cmService->deleteMovie($id))
            $this->addFlash('success','Movie deleted successfully');

        return $this->redirectToRoute('movies');
    }

    #[Route('/admin/comments/view', methods:['GET'], name: 'comments_adminpanel')]
    public function checkcomments(): Response
    {
        return $this->render('adminpanel/content_management/comments.html.twig', [
            'reviews' => $this->em->getRepository(Comments::class)->findAll()
        ]);
    }

    #[Route('/admin/comments/delete/{id}', methods:['GET','DELETE'], name: 'deletecomments_adminpanel')]
    public function deletecomments(int $id): Response
    {
        $this->cmService->deleteComments($id);
        $this->addFlash('success','Comment deleted successfully');

        return $this->redirectToRoute('comments_adminpanel');
    }

    #[Route('/admin/categories/view', methods:['GET'], name: 'categories_adminpanel')]
    public function categoriesView(): Response
    {
        return $this->render('adminpanel/content_management/categories.html.twig', [
            'categories' => $this->em->getRepository(Categories::class)->findAll()
        ]);
    }

    #[Route('/admin/categories/create', methods:['GET','POST'], name: 'categoriescreate_adminpanel')]
    public function categoriesCreate(Request $request): Response
    {
        $categorie = new Categories();
        $categorieForm = $this->createForm(CategoriesFormType::class, $categorie);

        $categorieForm->handleRequest($request);
        if($this->cmService->createCategorie($categorieForm))
            $this->addFlash('success','Category created successfully');

        return $this->render('adminpanel/content_management/categories/create.html.twig', [
            'categorieForm' => $this->createForm(CategoriesFormType::class)->createView()
        ]);
    }

    #[Route('/admin/categories/edit/{id}', methods:['GET','POST'], name: 'categoriesedit_adminpanel')]
    public function categoriesEdit(int $id, Request $request): Response
    {
        $categorie = $this->categoriesRepository->find($id);
        $categorieForm = $this->createForm(CategoriesFormType::class, $categorie);
        $categorieForm->handleRequest($request);
        $this->cmService->editCategorie($categorieForm, $categorie);

        return $this->render('adminpanel/content_management/categories/edit.html.twig', [
            'categorie' => $categorie,
            'categorieForm' => $categorieForm
        ]);
    }

    #[Route('/admin/categories/delete/{id}', methods: ['GET','DELETE'], name: 'delete_categories')]
    public function deleteCategorie(int $id): Response
    {
        if($this->cmService->deleteCategorie($id))
            $this->addFlash('success','Category deleted successfully');

        return $this->redirectToRoute('categories_adminpanel');
    }

}

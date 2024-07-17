<?php

namespace App\Controller\Visitor\Menu;


use App\Entity\Category;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Config\TurboConfig;

class MenuController extends AbstractController
{
    // chemin de Controller
    #[Route('/menu', name: 'visitor_menu_index', methods: ['GET'])]
    public function index(
        CategoryRepository $categoryRepository,
        PostRepository $postRepository
        ): Response
    {
        $categories = $categoryRepository->findAll();
       
        $pizzeClassique ="pizze classiques";
        $pizzeGourmet = "pizze gourmet";
        $posts= $postRepository->getProductsWithCategory($pizzeClassique);
        $postsGourmet= $postRepository->getProductsWithCategory($pizzeGourmet);

      
        // chemin de templates
        return $this->render('pages/visitor/menu/index.html.twig', [
            'categories' => $categories,
            'posts'      =>$posts,
            'pizzeGourmet' =>$postsGourmet
        ]);
    }

    #[Route('/menu/post/filter-by-category/{id}/{slug}', name: 'visitor_menu_post_filter_by_category', methods: ['GET'])]
    public function filterByCategory(
        CategoryRepository $categoryRepository,
        PostRepository $postRepository,
        Category $category,
        $id
    ): Response
    {
        $categories = $categoryRepository = $categoryRepository->findAll();
        $posts      = $postRepository->getProductsWithCategoryId($id);
        $category = $posts['0']->getCategory()->getName();
        $nbrposts = count($posts);

      

        return $this->render('pages/visitor/menu/filter.html.twig', [
            'categories' => $categories,
            'posts'      =>$posts,
            'categoryName'=>$category,
            'nombre' => $nbrposts           
        ]);
    }
}

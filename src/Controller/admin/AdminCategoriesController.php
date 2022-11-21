<?php

namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminCategoriesController
 *
 * @author Jean
 */
class AdminCategoriesController extends AbstractController {
    
    /**
     *
     * @var type String
     */
    private $pageCategoriesAdmin = "admin/admin.categories.html.twig";
    
    /**
     *
     * @var type CategorieRepository
     */
    private $categorieRepository;
    
   
    /**
     * 
     * @param CategorieRepository $categorieRepository
     */
    function __construct(CategorieRepository $categorieRepository){
        $this->categorieRepository = $categorieRepository;
    }
    
    /**
     * @Route("/admin/categories", name="admin.categories")
     * @return Response
     */
    public function index(): Response{
        $categories = $this->categorieRepository->findAllOrderBy('name', 'ASC');
        return $this->render($this->pageCategoriesAdmin, [
            'categories' => $categories
        ]);
    }
    
    /**
     * @Route("/admin/categories/tri/{champ}/{ordre}", name="admin.categories.sort")
     * @return Response
     */
    public function sort($champ, $ordre): Response{
        $categories = $this->categorieRepository->findAllOrderBy($champ, $ordre);
        return $this->render($this->pageCategoriesAdmin, [
            'categories' => $categories
        ]);
    }
    
    /**
     * @Route("/admin/categories/suppr/{id}", name="admin.categorie.suppr")
     * @param Categorie $categorie
     * @return Reponse
    */ 
    public function suppr(Categorie $categorie): Response{
        $this->categorieRepository->remove($categorie, true);
        return $this->redirectToRoute('admin.categories');
    }
}

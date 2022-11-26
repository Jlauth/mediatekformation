<?php

namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function __construct(CategorieRepository $categorieRepository){
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
     * @return Response
    */ 
    public function suppr(Categorie $categorie): Response{
        $this->categorieRepository->remove($categorie, true);
        return $this->redirectToRoute('admin.categories');
    }
    
    /**
     * @Route("/admin/categories/ajout", name="admin.categorie.ajout")
     * @param Request $request 
     * @return Response
     */
    public function ajout(Request $request): Response{
        $categorie = new Categorie();
        $formCategorie = $this->createForm(CategorieType::class, $categorie);
        $formCategorie->handleRequest($request);
        if($formCategorie->isSubmitted() && $formCategorie->isValid()){
            $this->categorieRepository->add($categorie, true);
            return $this->redirectToRoute('admin.categories');
        }
        return $this->render("admin/admin.categorie.ajout.html.twig", [
            'categorie' => $categorie,
            'formCategorie' => $formCategorie->createView()
        ]);
    }
}

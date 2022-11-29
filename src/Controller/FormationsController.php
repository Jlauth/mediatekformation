<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur des formations
 *
 * @author emds
 */
class FormationsController extends AbstractController {

    /**
     * 
     * @var type String
     */
    private $pagesFormations = "pages/formations.html.twig";
    
    /**
     * 
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository= $categorieRepository;
    }
    
    /**
     * @Route("/formations", name="formations")
     * @return Response
     */
    public function index(): Response{
        $formations = $this->formationRepository->findAllOrderByEmpty('title', 'ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->pagesFormations, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/formations/tri/{champ}/{ordre}/{table}", name="formations.sort")
     * @param type $champ
     * @param type $ordre
     * @param type $table
     * @return Response
     */
    public function sort($champ, $ordre, $table=""): Response{
        if($table!=""){
           $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        }
        else{
            $formations = $this->formationRepository->findAllOrderByEmpty($champ, $ordre);
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->pagesFormations, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }     
    
    /**
     * @Route("/formations/recherche/{champ}/{table}", name="formations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table=""): Response{
        if($this->isCsrfTokenValid('filtre_'.$champ, $request->get('_token'))){
            $valeur = $request->get("recherche");
            if($table != ""){
                $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table); 
            }else{
                $formations = $this->formationRepository->findByContainValueEmpty($champ, $valeur);
            }
            $categories = $this->categorieRepository->findAll();
            return $this->render($this->pagesFormations, [
                'formations' => $formations,
                'categories' => $categories,
                'valeur' => $valeur,
                'table' => $table
            ]);
        }
        return $this->redirectToRoute("formations");
    }
    
    /**
     * @Route("/formations/formation/{id}", name="formations.showone")
     * @param type $id
     * @return Response
     */
    public function showOne($id): Response{
        $formation = $this->formationRepository->find($id);
        return $this->render("pages/formation.html.twig", [
            'formation' => $formation
        ]);        
    }   
    
}
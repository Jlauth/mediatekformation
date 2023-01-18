<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FormationsController.
 *
 * @author Jean
 */
class FormationsController extends AbstractController
{
    private $pagesFormations = 'pages/formations.html.twig';
    private $formationRepository;
    private $categorieRepository;

    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * @Route("/formations", name="formations")
     */
    public function index(): Response
    {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();

        return $this->render($this->pagesFormations, [
                    'formations' => $formations,
                    'categories' => $categories,
        ]);
    }

    /**
     * @Route("/formations/tri/{champ}/{ordre}/{table}", name="formations.sort")
     *
     * @param type $champ
     * @param type $ordre
     * @param type $table
     */
    public function sort($champ, $ordre, $table = ''): Response
    {
        if ('' != $table) {
            $formations = $this->formationRepository->findAllOrderByTable($champ, $ordre, $table);
        } else {
            $formations = $this->formationRepository->findAllOrderBy($champ, $ordre);
        }
        $categories = $this->categorieRepository->findAll();

        return $this->render($this->pagesFormations, [
                    'formations' => $formations,
                    'categories' => $categories,
        ]);
    }

    /**
     * @Route("/formations/recherche/{champ}/{table}", name="formations.findallcontain")
     *
     * @param type $champ
     * @param type $table
     */
    public function findAllContain($champ, Request $request, $table = ''): Response
    {
        if ($this->isCsrfTokenValid('filtre_'.$champ, $request->get('_token'))) {
            $valeur = $request->get('recherche');
            if ('' != $table) {
                $formations = $this->formationRepository->findByContainValueTable($champ, $valeur, $table);
            } else {
                $formations = $this->formationRepository->findByContainValue($champ, $valeur);
            }
            $categories = $this->categorieRepository->findAll();

            return $this->render($this->pagesFormations, [
                        'formations' => $formations,
                        'categories' => $categories,
                        'valeur' => $valeur,
                        'table' => $table,
            ]);
        }

        return $this->redirectToRoute('formations');
    }

    /**
     * @Route("/formations/rechercher/{champ}/{table}", name="formations.findallcontaincategories")
     *
     * @param type $champ
     * @param type $table
     */
    public function findAllContainCategories($champ, Request $request, $table): Response
    {
        $valeur = $request->get('recherche');
        $formations = $this->formationRepository->findByContainValueTable($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();

        return $this->render($this->pagesFormations, [
                    'formations' => $formations,
                    'categories' => $categories,
                    'valeur' => $valeur,
                    'table' => $table,
        ]);
    }

    /**
     * @Route("/formations/formation/{id}", name="formations.showone")
     *
     * @param type $id
     */
    public function showOne($id): Response
    {
        $formation = $this->formationRepository->find($id);

        return $this->render('pages/formation.html.twig', [
                    'formation' => $formation,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of PlaylistsController
 *
 * @author emds
 */
class PlaylistsController extends AbstractController {

    /**
     * 
     * @var type String
     */
    private $pagePlaylists = "pages/playlists.html.twig";

    /**
     * 
     * @var PlaylistRepository
     */
    private $playlistRepository;

    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * 
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * Constructeur de la classe PlaylistController
     * @param PlaylistRepository $playlistRepository
     * @param CategorieRepository $categorieRepository
     * @param FormationRepository $formationRepository
     */
    function __construct(PlaylistRepository $playlistRepository,
            CategorieRepository $categorieRepository, FormationRepository $formationRepository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
    }

    /**
     * @Route("/playlists", name="playlists")
     * @return Response
     */
    public function index(): Response {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->pagePlaylists, [
                    'playlists' => $playlists,
                    'categories' => $categories
        ]);
    }

    /**
     * @Route("/playlists/tri/{champ}/{ordre}", name="playlists.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response {
        switch ($champ) {
            case "name":
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "nbformations":
                $playlists = $this->playlistRepository->findAllOrderByNbFormations($ordre);
                break;
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->pagePlaylists, [
                    'playlists' => $playlists,
                    'categories' => $categories
        ]);
    }

    /**
     * @Route("/playlists/recherche/{champ}/{table}", name="playlists.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table = ""): Response {
        if ($this->isCsrfTokenValid('filtre_' . $champ, $request->get('_token'))) {
            $valeur = $request->get("recherche");
            if ($table != "") {
                $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
            } else {
                $playlists = $this->playlistRepository->findByContainValueEmpty($champ, $valeur);
            }
            $categories = $this->categorieRepository->findAll();

            return $this->render($this->pagePlaylists, [
                        'playlists' => $playlists,
                        'categories' => $categories,
                        'valeur' => $valeur,
                        'table' => $table
            ]);
        }
        return $this->redirectToRoute("playlists");
    }

    /**
     * @Route("/playlists/playlist/{id}", name="playlists.showone")
     * @param type $id
     * @return Response
     */
    public function showOne($id): Response {
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render("pages/playlist.html.twig", [
                    'playlist' => $playlist,
                    'playlistcategories' => $playlistCategories,
                    'playlistformations' => $playlistFormations
        ]);
    }

    /**
     * @Route("/playlists/tri/{ordre}", name="playlists.sortonnbformation")
     * @param type $ordre
     * @return Response
     */
    public function sortOnNbFormation($ordre): Response {
        $playlists = $this->playlistRepository->findAllOrderByNbFormations($ordre);
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/playlists.html.twig", [
                    'playlists' => $playlists,
                    'categories' => $categories
        ]);
    }
}

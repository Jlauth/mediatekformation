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
 * Class PlaylistsController.
 *
 * @author Jean
 */
class PlaylistsController extends AbstractController
{
    private $pagesPlaylists = 'pages/playlists.html.twig';
    private $playlistRepository;
    private $categorieRepository;
    private $formationRepository;

    /**
     * Constructeur de la classe PlaylistController.
     */
    public function __construct(PlaylistRepository $playlistRepository,
            CategorieRepository $categorieRepository, FormationRepository $formationRepository)
    {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
    }

    /**
     * @Route("/playlists", name="playlists")
     */
    public function index(): Response
    {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();

        return $this->render($this->pagesPlaylists, [
                    'playlists' => $playlists,
                    'categories' => $categories,
        ]);
    }

    /**
     * @Route("/playlists/tri/{champ}/{ordre}", name="playlists.sort")
     *
     * @param type $champ
     * @param type $ordre
     */
    public function sort($champ, $ordre): Response
    {
        switch ($champ) {
            case 'name':
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case 'nbformations':
                $playlists = $this->playlistRepository->findAllOrderByNbFormation($ordre);
                break;
        }
        $categories = $this->categorieRepository->findAll();

        return $this->render($this->pagesPlaylists, [
                    'playlists' => $playlists,
                    'categories' => $categories,
        ]);
    }

    /**
     * @Route("/playlists/recherche/{champ}", name="playlists.findallcontain")
     *
     * @param type $champ
     */
    public function findAllContain($champ, Request $request): Response
    {
        if ($this->isCsrfTokenValid('filtre_'.$champ, $request->get('_token'))) {
            $valeur = $request->get('recherche');
            $playlists = $this->playlistRepository->findByContainValue($champ, $valeur);
            $categories = $this->categorieRepository->findAll();

            return $this->render($this->pagesPlaylists, [
                        'playlists' => $playlists,
                        'categories' => $categories,
                        'valeur' => $valeur,
            ]);
        }

        return $this->redirectToRoute('playlists');
    }

    /**
     * @Route("/playlists/recherche/{champ}/{table}", name="playlists.findallcontaincategories")
     *
     * @param type $champ
     * @param type $table
     */
    public function findAllContainCategories($champ, Request $request, $table): Response
    {
        $valeur = $request->get('recherche');
        $playlists = $this->playlistRepository->findByContainValueTable($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();

        return $this->render($this->pagesPlaylists, [
                    'playlists' => $playlists,
                    'categories' => $categories,
                    'valeur' => $valeur,
                    'table' => $table,
        ]);
    }

    /**
     * @Route("/playlists/playlist/{id}", name="playlists.showone")
     *
     * @param type $id
     */
    public function showOne($id): Response
    {
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);

        return $this->render('pages/playlist.html.twig', [
                    'playlist' => $playlist,
                    'playlistcategories' => $playlistCategories,
                    'playlistformations' => $playlistFormations,
        ]);
    }

    /**
     * @Route("/playlists/tri/{ordre}", name="playlists.sortonnbformation")
     *
     * @param type $ordre
     */
    public function sortOnNbFormation($ordre): Response
    {
        $playlists = $this->playlistRepository->findAllOrderByNbFormation($ordre);
        $categories = $this->categorieRepository->findAll();

        return $this->render($this->pagesPlaylists, [
                    'playlists' => $playlists,
                    'categories' => $categories,
        ]);
    }
}

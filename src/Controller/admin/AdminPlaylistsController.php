<?php

namespace App\Controller\admin;

use App\Entity\Formation;
use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Form\PlaylistTypeAdd;
use App\Repository\CategorieRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminPlaylistsController
 *
 * @author Jean
 */
class AdminPlaylistsController extends AbstractController {

    private $pagePlaylistsAdmin = "admin/admin.playlists.html.twig";
    private $redirectToAP = "admin.playlists";
    private $playlistRepository;
    private $categorieRepository;

    /**
     * Constructeur de la classe PlaylistController
     * @param PlaylistRepository $playlistRepository
     * @param CategorieRepository $categorieRepository
     */
    function __construct(PlaylistRepository $playlistRepository,
            CategorieRepository $categorieRepository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * @Route("/admin/playlists", name="admin.playlists")
     * @return Response
     */
    public function index(): Response {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->pagePlaylistsAdmin, [
                    'playlists' => $playlists,
                    'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/playlists/tri/{champ}/{ordre}", name="admin.playlists.sort")
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
                $playlists = $this->playlistRepository->findAllOrderByNbFormation($ordre);
                break;
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->pagePlaylistsAdmin, [
                    'playlists' => $playlists,
                    'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/playlists/recherche/{champ}", name="admin.playlists.findallcontain")
     * @param type $champ
     * @param Request $request
     * @return Response
     */
    public function findAllContain($champ, Request $request): Response {
        if ($this->isCsrfTokenValid('filtre_' . $champ, $request->get('_token'))) {
            $valeur = $request->get("recherche");
            $playlists = $this->playlistRepository->findByContainValue($champ, $valeur);
            $categories = $this->categorieRepository->findAll();
            return $this->render($this->pagePlaylistsAdmin, [
                        'playlists' => $playlists,
                        'categories' => $categories,
                        'valeur' => $valeur
            ]);
        }
        return $this->redirectToRoute($this->redirectToAP);
    }

    /**
     * @Route("/admin/playlists/recherche/{champ}/{table}", name="admin.playlists.findallcontaincategories")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContainCategories($champ, Request $request, $table): Response {
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValueTable($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->pagePlaylistsAdmin, [
                    'playlists' => $playlists,
                    'categories' => $categories,
                    'valeur' => $valeur,
                    'table' => $table
        ]);
    }

    /**
     * @Route("/admin/playlists/tri/{ordre}", name="admin.playlists.sortonnbformation")
     * @param type $ordre
     * @return Response
     */
    public function sortOnNbFormation($ordre): Response {
        $playlists = $this->playlistRepository->findAllOrderByNbFormation($ordre);
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->pagePlaylistsAdmin, [
                    'playlists' => $playlists,
                    'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/playlists/suppr/{id}", name="admin.playlist.suppr")
     * @param Formation $playlist
     * @return Response
     */
    public function suppr(Playlist $playlist): Response {
        $this->playlistRepository->remove($playlist, true);
        return $this->redirectToRoute($this->redirectToAP);
    }

    /**
     * @Route("/admin/playlists/edit/{id}", name="admin.playlist.edit")
     * @param Playlist $playlist
     * @param Request $request
     * @return Response
     */
    public function edit(Playlist $playlist, Request $request): Response {
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        $formPlaylist->handleRequest($request);
        if ($formPlaylist->isSubmitted() && $formPlaylist->isValid()) {
            $this->playlistRepository->add($playlist, true);
            return $this->redirectToRoute($this->redirectToAP);
        }
        return $this->render("admin/admin.playlist.edit.html.twig", [
                    'playlist' => $playlist,
                    'formPlaylist' => $formPlaylist->createView()
        ]);
    }

    /**
     * @Route("/admin/playlists/ajout", name="admin.playlist.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response {
        $playlist = new Playlist();
        $formPlaylistAdd = $this->createForm(PlaylistTypeAdd::class, $playlist);
        $formPlaylistAdd->handleRequest($request);
        if ($formPlaylistAdd->isSubmitted() && $formPlaylistAdd->isValid()) {
            $this->playlistRepository->add($playlist, true);
            return $this->redirectToRoute($this->redirectToAP);
        }
        return $this->render("admin/admin.playlist.ajout.html.twig", [
                    'playlist' => $playlist,
                    'formPlaylistAdd' => $formPlaylistAdd->createView()
        ]);
    }

}

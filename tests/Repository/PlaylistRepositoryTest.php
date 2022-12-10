<?php

namespace App\Tests\Repository;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of PlaylistRepositoryTest
 *
 * @author Jean
 */
class PlaylistRepositoryTest extends KernelTestCase {

    /**
     * Récupération des méthodes de Playlist Repository
     * @return PlaylistRepository
     */
    protected function getRepository(): PlaylistRepository {
        self::bootKernel();
        $repository = self::getContainer()->get(PlaylistRepository::class);
        return $repository;
    }

    /**
     * Création d'une instance de Playlist avec name et description
     * @return Playlist
     */
    public function newPlaylist(): Playlist {
        $playlist = (new Playlist())
                ->setName("Je suis une playlist de test")
                ->setDescription("Je suis la description d'une playlist de test");
        return $playlist;
    }

    /**
     * Test sur l'ajout d'une playlist
     */
    public function testAddPlaylist() {
        $repository = $this->getRepository();
        $playlist = $this->newPlaylist();
        $nbPlaylists = $repository->count([]);
        $repository->add($playlist, true);
        $this->assertEquals($nbPlaylists + 1, $repository->count([]), "Erreur lors de l'ajout");
    }

    /**
     * Test sur la suppression d'une playlist
     */
    public function testRemovePlaylist() {
        $repository = $this->getRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $nbPlaylists = $repository->count([]);
        $repository->remove($playlist, true);
        $this->assertEquals($nbPlaylists - 1, $repository->count([]), "Erreur lors de la suppresion");
    }

    /**
     * Test sur la méthode findAllOrderByName
     */
    public function testFindAllOderByName() {
        $repository = $this->getRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $playlists = $repository->findAllOrderByName("ASC");
        $nbPlaylists = count($playlists);
        $this->assertEquals(28, $nbPlaylists);
        $this->assertEquals("Cours Curseurs", $playlists[3]->getName());
    }

    /**
     * Test sur la méthode findAllOrderByNbFormations
     */
    public function testFindAllOrderByNbFormations() {
        $repository = $this->getRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $playlists = $repository->findAllOrderByNbFormation("ASC");
        $nbPlaylists = count($playlists);
        $this->assertEquals(28, $nbPlaylists);
        $this->assertEquals("Cours Merise/2", $playlists[2]->getName());
    }

    /**
     * Test sur la méthode findByContainValue
     */
    public function testFindByContainValue() {
        $repository = $this->getRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $playlists = $repository->findByContainValue("name", "Eclipse et Java");
        $nbPlaylists = count($playlists);
        $this->assertEquals(1, $nbPlaylists);
        $this->assertEquals("Eclipse et Java", $playlists[0]->getName());
    }
}

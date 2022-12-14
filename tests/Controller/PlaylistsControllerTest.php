<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of PlaylistsControllerTest
 *
 * @author Jean
 */
class PlaylistsControllerTest extends WebTestCase {

    private $playlistPage = '/playlists';
    
    /**
     * Initialisation du client de test d'accès à la page playlists
     */
    public function testAccessPage() {
        $client = static::createClient();
        $client->request('GET', $this->playlistPage);
        /** @var type $response */
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * Test tri playlists dans Playlists
     */
    public function testTriPlaylistByName(){
        $client = static::createClient();
        $crawler = $client->request('GET', $this->playlistPage);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals($this->playlistPage, $uri);
        $this->assertSelectorTextContains('th', 'Playlist');
        $this->assertCount(27, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Bases de la programmation (C#)');
    }
   
    
    /**
     * Test filtre playlist name dans Playlists
     */
    public function testFiltrePlaylistName() {
        $client = static::createClient();
        $client->request('GET', $this->playlistPage);
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Cours'
        ]);
        $this->assertCount(11, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Cours');
    }
    
    /**
     * Test tri categorie dans Playlists
     */
    public function testFiltrePlaylistCategorie(){
        $client = static::createClient();
        $client->request('GET', $this->playlistPage);
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Android'
        ]);
        $this->assertCount(3, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Android');
    }

    
    /**
     * Test sur la réponse du clic bouton "Voir détail"
     */
    public function testLinkPlaylist() {
        $client = static::createClient();
        $client->request('GET', $this->playlistPage);
        $client->clickLink('Voir détail');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/playlists/playlist/13', $uri);
    }
}

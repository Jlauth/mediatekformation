<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of FormationsControllerTest
 *
 * @author Jean
 */
class FormationsControllerTest extends WebTestCase {

    /**
     * Test d'accès à la page Formations
     */
    public function testAccessPage() {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * Test filtre formation title dans Formations
     */
    public function testFiltreFormations() {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Android'
        ]);
        $this->assertSelectorTextContains('h5', 'Android');
        $this->assertCount(64, $crawler->filter('h5'));
    }

    /**
     * Test tri formation dans Formations
     */
    public function testTriFormations() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $this->assertSelectorTextContains('h4', 'Formation');
        $this->assertCount(239, $crawler->filter('h4'));
        $this->assertSelectorTextContains('h5', 'Compléments Android (programmation mobile)');
    }
    
    /**
     * Test lien vers une formation dans Formations
     */
    public function testLinkFormations() {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $client->clickLink('Images des formations');
        $response = $client->getResponse();
        //dd($client->getRequest());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/formation/89', $uri);
    }
}

    /**
     * Test tri playlist dans Formations
    
    public function testTriFormationsPlaylist() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $this->assertSelectorTextContains('h4', 'Playlist');
        $this->assertCount(74, $crawler->filter('h4'));
  
   

     * Test filtre playlist dans Formations

      public function testFiltreFormationsPlaylistName() {
      $client = static::createClient();
      $client->request('GET', '/formations');
      $crawler = $client->submitForm('filtrer', [
      'recherche' => 'Compléments'
      ]);
      $this->assertCount(5, $crawler->filter('h5'));
      $this->assertSelectorTextContains('h5', 'Compléments');
      }


     * Test filtre catégorie dans Formations
     */


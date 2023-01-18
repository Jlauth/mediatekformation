<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of AccueilControllerTest.
 *
 * @author Jean
 */
class AccueilControllerTest extends WebTestCase
{
    /**
     * Initialisation du client de test d'accès à la page d'accueil.
     */
    public function testAccessPageAccueil()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        /** @var type $response */
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * Test link formations depuis Accueil.
     */
    public function testLinkFormationsAccueil()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $client->clickLink('Formations');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get('REQUEST_URI');
        $this->assertEquals('/formations', $uri);
    }

    /**
     * Test link playlists depuis Accueil.
     */
    public function testLinkPlaylistsAccueil()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $client->clickLink('Playlists');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get('REQUEST_URI');
        $this->assertEquals('/playlists', $uri);
    }

    /**
     * Test link CGU depuis Accueil.
     */
    public function testLinkCguAccueil()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $client->clickLink('Conditions Générales d\'Utilisation');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get('REQUEST_URI');
        $this->assertEquals('/cgu', $uri);
    }

    /**
     * Test link accueil depuis Accueil.
     */
    public function testLinkAccueil()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $client->clickLink('Accueil');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get('REQUEST_URI');
        $this->assertEquals('/', $uri);
    }
}

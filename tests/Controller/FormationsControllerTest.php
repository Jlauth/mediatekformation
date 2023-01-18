<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of FormationsControllerTest.
 *
 * @author Jean
 */
class FormationsControllerTest extends WebTestCase
{
    private $formationsPage = '/formations';

    /**
     * Test d'accès à la page Formations.
     */
    public function testAccessPage()
    {
        $client = static::createClient();
        $client->request('GET', $this->formationsPage);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * Test tri formation dans Formations.
     */
    public function testTriFormations()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $this->formationsPage);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get('REQUEST_URI');
        $this->assertEquals($this->formationsPage, $uri);
        $this->assertSelectorTextContains('th', 'Formation');
        $this->assertCount(237, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Eclipse n°8 : Déploiement');
    }

    /**
     * Test filtre formation title dans Formations.
     */
    public function testFiltreFormations()
    {
        $client = static::createClient();
        $client->request('GET', $this->formationsPage);
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Android',
        ]);
        $this->assertSelectorTextContains('h5', 'Android');
        $this->assertCount(32, $crawler->filter('h5'));
    }

    /**
     * Test lien vers une formation dans Formations.
     */
    public function testLinkFormations()
    {
        $client = static::createClient();
        $client->request('GET', $this->formationsPage);
        $client->clickLink('Images des formations');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get('REQUEST_URI');
        $this->assertEquals('/formations/formation/1', $uri);
    }
}

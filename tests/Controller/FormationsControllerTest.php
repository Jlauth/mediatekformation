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

    private $formationsPage = '/formations';
    /**
     * Test d'accès à la page Formations
     */
    public function testAccessPage() {
        $client = static::createClient();
        $client->request('GET', $this->formationsPage);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * Test filtre formation title dans Formations
     */
    public function testFiltreFormations() {
        $client = static::createClient();
        $client->request('GET', $this->formationsPage);
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Android'
        ]);
        $this->assertSelectorTextContains('h5', 'Android');
        $this->assertCount(32, $crawler->filter('h5'));
    }

    /**
     * Test tri formation dans Formations
     */
    public function testTriFormations() {
        $client = static::createClient();
        $crawler = $client->request('GET', $this->formationsPage);
        $this->assertSelectorTextContains('th', 'Formation');
        $this->assertCount(5, $crawler->filter('th'));
        $this->assertSelectorTextContains('th', 'Compléments Android (programmation mobile)');
    }
    
    /**
     * Test lien vers une formation dans Formations
     */
    public function testLinkFormations() {
        $client = static::createClient();
        $client->request('GET', $this->formationsPage);
        $client->clickLink('Images des formations');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/formation/89', $uri);
    }
}


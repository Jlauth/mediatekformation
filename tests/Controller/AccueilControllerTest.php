<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of AccueilControllerTest
 *
 * @author Jean
 */
class AccueilControllerTest extends WebTestCase{
    
    /**
     * Initialisation du client de test d'accès à la page d'accueil
     */
    public function testAccessPage(){
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
}

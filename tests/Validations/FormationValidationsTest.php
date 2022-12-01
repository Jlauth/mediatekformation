<?php

namespace App\Tests\Validations;

use App\Entity\Formation;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Description of FormationValidationsTest
 *
 * @author Jean
 */
class FormationValidationsTest extends KernelTestCase {

    /**
     * Initialisation d'une nouvelle formation
     * @return Formation
     */
    public function getFormation() : Formation{
        return (new Formation())
            ->setPublishedAt(new DateTime("2021-11-29 22:15:45"))
            ->setTitle("Je suis un titre de formation");
    }
    
    /**
     * Méthode d'initialisation du Kernel, container et interface de validation
     * @param Formation $formation
     * @param int $nbErreursAttendues
     */
    public function assertErrors(Formation $formation, int $nbErreursAttendues){
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount($nbErreursAttendues, $error);
    }
    
    /**
     * Test sur la validité d'une date
     */
    public function testValidDateFormation(){
        $formation = $this->getFormation()->setPublishedAt(new DateTime("2021-11-29 22:15:45"));
        $this->assertErrors($formation, 0);
    }
}

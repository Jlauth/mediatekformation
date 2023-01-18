<?php

namespace App\Tests;

use App\Entity\Formation;
use PHPUnit\Framework\TestCase;

/**
 * Description of FormationTest.
 *
 * @author Jean
 */
class FormationTest extends TestCase
{
    public function testGetPublishedAt()
    {
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTime('2022-11-29'));
        $this->assertEquals('29/11/2022', $formation->getPublishedAtString());
    }
}

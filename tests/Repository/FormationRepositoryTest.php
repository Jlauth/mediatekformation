<?php

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of FormationRepositoryTest.
 *
 * @author Jean
 */
class FormationRepositoryTest extends KernelTestCase
{
    /**
     * Méthode de récupération des méthodes de Formation Repository.
     */
    public function getRepository(): FormationRepository
    {
        self::bootKernel();
        $repository = self::getContainer()->get(FormationRepository::class);

        return $repository;
    }

    /**
     * Création d'une instance de Formation avec date publishedAt, title et description.
     */
    public function newFormation(): Formation
    {
        $formation = (new Formation())
                ->setPublishedAt(new \DateTime('04/01/2021'))
                ->setTitle("Test d'intégration Formation")
                ->setDescription("Je suis une description test du test d'intégration");

        return $formation;
    }

    /**
     * Test sur l'ajout d'une formation.
     */
    public function testAddFormation()
    {
        $repository = $this->getRepository();
        $formation = $this->newFormation();
        $nbFormations = $repository->count([]);
        $repository->add($formation, true);
        $this->assertEquals($nbFormations + 1, $repository->count([]), "Erreur lors de l'ajout");
    }

    /**
     * Test sur l'ajout puis la suppression d'une formation.
     */
    public function testRemoveFormation()
    {
        $repository = $this->getRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $nbFormations = $repository->count([]);
        $repository->remove($formation, true);
        $this->assertEquals($nbFormations - 1, $repository->count([]), 'Erreur lors de la suppresion');
    }

    /**
     * Test sur la méthode findAllOrderBy.
     */
    public function testFindAllOrderBy()
    {
        $repository = $this->getRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findAllOrderBy('id', 'ASC');
        $nbFormations = count($formations);
        $this->assertEquals(238, $nbFormations);
        $this->assertEquals('Eclipse n°8 : Déploiement', $formations[0]->getTitle());
    }

    /**
     * Test sur la méthode findByContainValueEmpty.
     */
    public function testfindByContainValue()
    {
        $repository = $this->getRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findByContainValue('title', 'Eclipse');
        $nbFormations = count($formations);
        $this->assertEquals(9, $nbFormations);
        $this->assertEquals('Eclipse n°8 : Déploiement', $formations[0]->getTitle());
    }

    /**
     * Test sur la méthode findAllOrderByEmpty.
     */
    public function testFindAllOrderByTable()
    {
        $repository = $this->getRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findAllOrderByTable('name', 'DESC', 'categories');
        $nbFormations = count($formations);
        $this->assertEquals(224, $nbFormations);
        $this->assertEquals('Eclipse n°2 : rétroconception avec ObjectAid', $formations[0]->getTitle());
    }

    /**
     * Test sur la méthode findAllLast.
     */
    public function testfindAllLasted()
    {
        $repository = $this->getRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findAllLasted(1);
        $nbFormations = count($formations);
        $this->assertEquals(1, $nbFormations);
        $this->assertEquals(new \DateTime('04/01/2021'), $formations[0]->getPublishedAt());
    }

    /**
     * Test sur la méthode findAllForOnePlaylist.
     */
    public function testFindAllForOnePlaylist()
    {
        $repository = $this->getRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findAllForOnePlaylist(1);
        $nbFormations = count($formations);
        $this->assertEquals(8, $nbFormations);
        $this->assertEquals("Eclipse n°1 : installation de l'IDE", $formations[0]->getTitle());
    }
}

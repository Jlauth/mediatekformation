<?php

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of FormationRepositoryTest
 *
 * @author Jean
 */
class FormationRepositoryTest extends KernelTestCase {

    /**
     * Méthode de récupération des méthodes de Formation Repository
     * @return FormationRepository
     */
    public function getRepository(): FormationRepository {
        self::bootKernel();
        $repository = self::getContainer()->get(FormationRepository::class);
        return $repository;
    }

    /**
     * Création d'une instance de Formation avec date publishedAt, title et description
     * @return Formation
     */
    public function newFormation(): Formation {
        $formation = (new Formation())
                ->setPublishedAt(new \DateTime("now"))
                ->setTitle("Test d'intégration Formation")
                ->setDescription("Je suis une description test du test d'intégration");
        return $formation;
    }

    /**
     * Test sur l'ajout d'une formation 
     */
    public function testAddFormation() {
        $repository = $this->getRepository();
        $formation = $this->newFormation();
        $nbFormations = $repository->count([]);
        $repository->add($formation, true);
        $this->assertEquals($nbFormations + 1, $repository->count([]), "Erreur lors de l'ajout");
    }

    /**
     * Test sur l'ajout puis la suppression d'une formation
     */
    public function testRemoveFormation() {
        $repository = $this->getRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $nbFormations = $repository->count([]);
        $repository->remove($formation, true);
        $this->assertEquals($nbFormations - 1, $repository->count([]), "Erreur lors de la suppresion");
    }

    /**
     * Test sur la méthode findAllOrderByEmpty
     */
    public function testFindAllOrderByEmpty() {
        $repository = $this->getRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        /** @var type $formations */
        $formations = $repository->findAllOrderByEmpty("title", "ASC");
        $nbFormations = count($formations);
        $this->assertEquals(238, $nbFormations);
    }

    /**
     * Test sur la méthode findAllOrderBy
     */
    public function testFindAllOrderBy() {
        $repository = $this->getRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        /** @var type $formations */
        $formations = $repository->findAllOrderBy("name", "ASC", "categories");
        $nbFormations = count($formations);
        $this->assertEquals(224, $nbFormations);
    }

    /**
     * Test sur la méthode findByContainValueEmpty
     */
    public function testfindByContainValueEmpty() {
        $repository = $this->getRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findByContainValueEmpty("title", "Test d'intégration Formation");
        $nbFormations = count($formations);
        $this->assertEquals(1, $nbFormations);
        $this->assertEquals("Test d'intégration Formation", $formations[0]->getTitle());
    }
    
    /**
     * Test sur la méthode findAllLast
     */
    public function testfindAllLast(){
        $repository = $this->getRepository();
        $formations = $repository->findAllLasted(1);
        $nbFormations = count($formations);
        $this->assertEquals(1, $nbFormations);
    }
    
    /**
     * Test sur la méthode findAllForOnePlaylist
     */
    public function testFindAllForOnePlaylist(){
        $repository = $this->getRepository();
        $formations = $repository->findAllForOnePlaylist(0);
        $nbFormations = count($formations);
        $this->assertEquals(0, $nbFormations);
    }
}

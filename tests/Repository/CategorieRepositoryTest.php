<?php

namespace App\Tests\Repository;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of CategorieRepositoryTest.
 *
 * @author Jean
 */
class CategorieRepositoryTest extends KernelTestCase
{
    /**
     * Méthode de récupération des méthodes de Playlist Repository.
     *
     * @return PlaylistRepository
     */
    public function getRepository(): CategorieRepository
    {
        self::bootKernel();
        $repository = self::getContainer()->get(CategorieRepository::class);

        return $repository;
    }

    /**
     * Création d'une instance de Playlist avec name et description.
     */
    public function newCategorie(): Categorie
    {
        $categorie = (new Categorie())
                ->setName('Je suis une catégorie de test');

        return $categorie;
    }

    /**
     * Test sur l'ajout d'une categorie.
     */
    public function testAddCategorie()
    {
        $repository = $this->getRepository();
        $categorie = $this->newCategorie();
        $nbCategories = $repository->count([]);
        $repository->add($categorie, true);
        $this->assertEquals($nbCategories + 1, $repository->count([]), "Erreur lors de l'ajout");
    }

    /**
     * Test sur la suppression d'une categorie.
     */
    public function testRemoveCategorie()
    {
        $repository = $this->getRepository();
        $categorie = $this->newCategorie();
        $repository->add($categorie, true);
        $nbCategories = $repository->count([]);
        $repository->remove($categorie, true);
        $this->assertEquals($nbCategories - 1, $repository->count([]), 'Erreur lors de la suppresion');
    }

    /**
     * Test sur la méthode findAllOrderBy.
     */
    public function testFindAllOrderBy()
    {
        $repository = $this->getRepository();
        $categorie = $this->newCategorie();
        $repository->add($categorie, true);
        $categories = $repository->findAllOrderBy('id', 'DESC');
        $nbCategories = count($categories);
        $this->assertEquals(10, $nbCategories);
        $this->assertEquals('Je suis une catégorie de test', $categories[0]->getName());
    }

    /**
     * Test sur la méthode findAllForOnePlaylist.
     */
    public function testFindAllFOrOnePlaylist()
    {
        $repository = $this->getRepository();
        $categorie = $this->newCategorie();
        $repository->add($categorie, true);
        $categories = $repository->findAllForOnePlaylist(1);
        $nbCategories = count($categories);
        $this->assertEquals(2, $nbCategories);
        $this->assertEquals('Java', $categories[0]->getName());
    }
}

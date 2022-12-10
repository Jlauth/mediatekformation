<?php

namespace App\Repository;

use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Playlist>
 *
 * @method Playlist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Playlist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Playlist[]    findAll()
 * @method Playlist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaylistRepository extends ServiceEntityRepository {

    private $idPlaylist = 'p.id';
    private $namePlaylist = 'p.name';
    private $formations = 'p.formations';
    private $categories = 'f.categories';

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Playlist::class);
    }

    public function add(Playlist $entity, bool $flush = false): void {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Playlist $entity, bool $flush = false): void {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Tri sur le nom d'une playlist
     * @param type $ordre
     * @return Playlist[]
     */
    public function findAllOrderByName($ordre): array {
        return $this->createQueryBuilder('p')
                        ->leftjoin($this->formations, 'f')
                        ->groupBy($this->idPlaylist)
                        ->orderBy($this->namePlaylist, $ordre)
                        ->getQuery()
                        ->getResult();
    }

    /**
     * Retourne toutes les playlists triées sur le nombre de formations
     * @param type $ordre
     * @return Playlist[]
     */
    public function findAllOrderByNbFormation($ordre): array {
        return $this->createQueryBuilder('p')
                        ->leftjoin($this->formations, 'f')
                        ->groupBy($this->idPlaylist)
                        ->orderBy('count(f.title)', $ordre)
                        ->getQuery()
                        ->getResult();
    }

    /**
     * Enregistrements dont un champ contient une valeur
     * Ou tous les enregistrements si la valeur est null
     * @param type $champ
     * @param type $valeur
     * @return Playlist[]
     */
    public function findByContainValue($champ, $valeur): array {
        if ($valeur == "") {
            return $this->findAll();
        } else {
            return $this->createQueryBuilder('p')
                            ->leftjoin($this->formations, 'f')
                            ->where('p.' . $champ . ' LIKE :valeur')
                            ->setParameter('valeur', '%' . $valeur . '%')
                            ->groupBy($this->idPlaylist)
                            ->orderBy($this->namePlaylist, 'ASC')
                            ->getQuery()
                            ->getResult();
        }
    }

    /**
     * Retourne la valeur renseignée en fonction du champ
     * Ou tous les enregistrements si la valeur est null
     * Avec $champ présent dans une autre entité
     * @param type $champ
     * @param type $valeur
     * @param type $table si $champ dans une autre table
     * @return array
     */
    public function findByContainValueTable($champ, $valeur, $table): array {
        if ($valeur == "") {
            return $this->findAll();
        } else {
            return $this->createQueryBuilder('p')
                            ->leftjoin($this->formations, 'f')
                            ->leftjoin($this->categories, 'c')
                            ->where('c.' . $champ . ' LIKE :valeur')
                            ->setParameter('valeur', '%' . $valeur . '%')
                            ->groupBy($this->idPlaylist)
                            ->orderBy($this->namePlaylist, 'ASC')
                            ->getQuery()
                            ->getResult();
        }
    }

}

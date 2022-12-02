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

    // Propriétés privées de la classe PlayListRepository
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
     * Retourne toutes les playlists triées sur le nom de la playlist
     * @param type $champ
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
    public function findAllOrderByNbFormations($ordre): array {
        return $this->createQueryBuilder('p')
                        ->leftjoin($this->formations, 'f')
                        ->groupBy($this->idPlaylist)
                        ->orderBy('count(f.title)', $ordre)
                        ->getQuery()
                        ->getResult();
    }

    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $champ
     * @param type $valeur
     * @param type $table si $champ dans une autre table
     * @return Playlist[]
     */
    public function findByContainValue($champ, $valeur, $table = ""): array {
        if ($valeur == "") {
            return $this->findAllOrderByName('ASC');
        } else {
            if ($table == "") {
                return $this->createQueryBuilder('p')
                                ->leftjoin($this->formations, 'f')
                                ->where('p.' . $champ . ' LIKE :valeur')
                                ->setParameter('valeur', '%' . $valeur . '%')
                                ->groupBy($this->idPlaylist)
                                ->orderBy($this->namePlaylist, 'ASC')
                                ->getQuery()
                                ->getResult();
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
}

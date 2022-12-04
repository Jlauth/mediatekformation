<?php

namespace App\Repository;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Formation>
 *
 * @method Formation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formation[]    findAll()
 * @method Formation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationRepository extends ServiceEntityRepository {

    private $publishedAt = 'f.publishedAt';

    /**
     * Constructeur de FormationRepository
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Formation::class);
    }

    /**
     * Ajout d'une formation
     * @param Formation $entity
     * @param bool $flush
     * @return void
     */
    public function add(Formation $entity, bool $flush = false): void {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Suppression d'une formation
     * @param Formation $entity 
     * @param bool $flush
     * @return void
     */
    public function remove(Formation $entity, bool $flush = false): void {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Tri d'un champ champ de la Formation
     * @param type $champ
     * @param type $ordre
     * @return Formation[]
     */
    public function findAllOrderBy($champ, $ordre): array {
        return $this->createQueryBuilder('f')
                        ->orderBy('f.' . $champ, $ordre)
                        ->getQuery()
                        ->getResult();
    }

    /**
     * Tri d'un champ d'une autre table que Formation
     * @param type $champ
     * @param type $table avec $champ dans une autre table
     * @param type $ordre
     * @return Formation[]
     */
    public function findAllOrderByTable($champ, $ordre, $table): array {
        return $this->createQueryBuilder('f')
                        ->join('f.' . $table, 't')
                        ->orderBy('t.' . $champ, $ordre)
                        ->getQuery()
                        ->getResult();
    }

    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide dans Formation
     * @param type $champ
     * @param type $valeur
     * @return Formation[]
     */
    public function findByContainValue($champ, $valeur): array {
        if ($valeur == "") {
            return $this->findAll();
        } else {
            return $this->createQueryBuilder('f')
                            ->where('f.' . $champ . ' LIKE :valeur')
                            ->orderBy($this->publishedAt, 'DESC')
                            ->setParameter('valeur', '%' . $valeur . '%')
                            ->getQuery()
                            ->getResult();
        }
    }

    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide dans une autre table
     * @param type $champ
     * @param type $valeur
     * @param type $table avec $champ dans une autre table
     * @return Formation[]
     */
    public function findByContainValueTable($champ, $valeur, $table): array {
        if ($valeur == "") {
            return $this->findAll();
        } else {
            return $this->createQueryBuilder('f')
                            ->join('f.' . $table, 't')
                            ->where('t.' . $champ . ' LIKE :valeur')
                            ->orderBy($this->publishedAt, 'DESC')
                            ->addOrderBy('f.id', 'DESC')
                            ->setParameter('valeur', '%' . $valeur . '%')
                            ->getQuery()
                            ->getResult();
        }
    }

    /**
     * Retourne les n formations les plus récentes
     * @param type $nb
     * @return Formation[]
     */
    public function findAllLasted($nb): array {
        return $this->createQueryBuilder('f')
                        ->orderBy($this->publishedAt, 'DESC')
                        ->setMaxResults($nb)
                        ->getQuery()
                        ->getResult();
    }

    /**
     * Retourne la liste des formations d'une playlist
     * @param type $idPlaylist
     * @return Formation[]
     */
    public function findAllForOnePlaylist($idPlaylist): array {
        return $this->createQueryBuilder('f')
                        ->join('f.playlist', 'p')
                        ->where('p.id=:id')
                        ->setParameter('id', $idPlaylist)
                        ->orderBy($this->publishedAt, 'ASC')
                        ->getQuery()
                        ->getResult();
    }
}

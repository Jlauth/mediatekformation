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
class PlaylistRepository extends ServiceEntityRepository
{
    // Propriétés privées de la classe PlayListRepository
    private $id = 'p.id id';
    private $name = 'p.name name';
    private $nameCategory = 'c.name categoriename';
    private $formations = 'p.formations';
    private $categories = 'f.categories';
    private $nameCategories = 'c.name';
    
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    public function add(Playlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Playlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    /**
     * Retourne toutes les playlists triées sur un champ
     * @param type $champ
     * @param type $ordre
     * @return Playlist[]
     */
    public function findAllOrderBy($champ, $ordre): array{
        return $this->createQueryBuilder('p')
                ->select($this->id)
                ->addSelect($this->name)
                ->addSelect($this->nameCategory)
                ->leftjoin($this->formations, 'f')
                ->leftjoin($this->categories, 'c')
                ->groupBy('p.id')
                ->addGroupBy($this->nameCategories)
                ->orderBy('p.'.$champ, $ordre)
                ->addOrderBy($this->nameCategories)
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
    public function findByContainValue($champ, $valeur, $table=""): array{
        if($valeur==""){
            return $this->findAllOrderBy('name', 'ASC');
        }    
        if($table==""){      
            return $this->createQueryBuilder('p')
                    ->select($this->id)
                    ->addSelect($this->name)
                    ->addSelect($this->nameCategory)
                    ->leftjoin($this->formations, 'f')
                    ->leftjoin($this->categories, 'c')
                    ->where('p.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->groupBy('p.id')
                    ->addGroupBy($this->nameCategories)
                    ->orderBy('p.name', 'ASC')
                    ->addOrderBy($this->nameCategories)
                    ->getQuery()
                    ->getResult();              
        }else{   
            return $this->createQueryBuilder('p')
                    ->select($this->id)
                    ->addSelect($this->name)
                    ->addSelect($this->nameCategory)
                    ->leftjoin($this->formations, 'f')
                    ->leftjoin($this->categories, 'c')
                    ->where('c.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->groupBy('p.id')
                    ->addGroupBy($this->nameCategories)
                    ->orderBy('p.name', 'ASC')
                    ->addOrderBy($this->nameCategories)
                    ->getQuery()
                    ->getResult();              
            
        }           
    }    


    
}

<?php

namespace App\Entity;

use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Range;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass=PlaylistRepository::class)
 * @UniqueEntity(fields="name", message="Ce nom de playlist existe déjà")
 */
class Playlist
{
    /**
     * @var integer $id
     * 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string $name
     * 
     * @ORM\Column(name="name", type="string", nullable=false, unique=true)
     * @Assert\Length(min=4, max=20, minMessage="Minimum {{ limit }} caractères", maxMessage="Maximum {{ limit }} caractères. C'est déjà pas mal non?")
     */
    private $name;

    /**
     * @var text $description
     * 
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Formation::class, mappedBy="playlist")
     */
    private $formations;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    /**
     * 
     * @param Formation $formation
     * @return self
     */
    public function addFormation(Formation $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations[] = $formation;
            $formation->setPlaylist($this);
        }

        return $this;
    }

    /**
     * 
     * @param Formation $formation
     * @return self
     */
    public function removeFormation(Formation $formation): self
    {
        if (($this->formations->removeElement($formation)) && ($formation->getPlaylist() === $this)) {
                $formation->setPlaylist(null);
        }
        return $this;
    }
    
     /**
     * @return Collection<int, string>
     */	
	public function getCategoriesPlaylist() : Collection
	{
            $categories = new ArrayCollection();
            foreach($this->formations as $formation){
                    $categoriesFormation = $formation->getCategories();
                    foreach ($categoriesFormation as $categorieFormation) {
                        if (!$categories->contains($categorieFormation->getName())) {
                            $categories[] = $categorieFormation->getName();
                        }
                    }
            }
            return $categories;
	}
}

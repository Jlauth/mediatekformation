<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=FormationRepository::class)
 */
class Formation {

    /**
     * Début de chemin vers les images
     */
    private const CHEMINIMAGE = "https://i.ytimg.com/vi/";

    /**
     * @var integer $id
     * 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var datetime $publishedAt
     * 
     * @ORM\Column(type="datetime", nullable=false)
     * @Assert\NotBlank()
     * @Assert\LessThanOrEqual("now")
     */
    private $publishedAt;

    /**
     * @var string $title
     * 
     * @ORM\Column(name="title", type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min=4, max=100)
     */
    private $title;

    /**
     * @var text $description
     * 
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Assert\Length(min=6, max=255)
     */
    private $description;

    /**
     * @var string $videoId
     * 
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $videoId;

    /**
     * @ORM\ManyToOne(targetEntity=Playlist::class, inversedBy="formations")
     */
    private $playlist;

    /**
     * @ORM\ManyToMany(targetEntity=Categorie::class, inversedBy="formations")
     */
    private $categories;
    
    
    public function __construct() {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getPublishedAt(): ?DateTimeInterface {
        return $this->publishedAt;
    }

    public function setPublishedAt(?DateTimeInterface $publishedAt): self {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getPublishedAtString(): string {
        if ($this->publishedAt == null) {
            return "";
        }
        return $this->publishedAt->format('d/m/Y');
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(?string $title): self {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): self {
        $this->description = $description;

        return $this;
    }

    public function getMiniature(): ?string {
        return self::CHEMINIMAGE . $this->videoId . "/default.jpg";
    }

    public function getPicture(): ?string {
        return self::CHEMINIMAGE . $this->videoId . "/hqdefault.jpg";
    }

    public function getVideoId(): ?string {
        return $this->videoId;
    }

    public function setVideoId(?string $videoId): self {
        $this->videoId = $videoId;

        return $this;
    }

    public function getPlaylist(): ?Playlist {
        return $this->playlist;
    }

    public function setPlaylist(?Playlist $playlist): self {
        $this->playlist = $playlist;

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategories(): Collection {
        return $this->categories;
    }

    public function addCategory(Categorie $category): self {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Categorie $category): self {
        $this->categories->removeElement($category);

        return $this;
    }

}

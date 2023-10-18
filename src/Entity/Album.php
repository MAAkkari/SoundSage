<?php

namespace App\Entity;

use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]
class Album
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'albumsLiker')]
    private Collection $likerPar;

    #[ORM\OneToMany(mappedBy: 'album', targetEntity: Musique::class)]
    private Collection $musiques;

    #[ORM\ManyToMany(targetEntity: Groupe::class, inversedBy: 'albums')]
    private Collection $groupes;

    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'albums')]
    private Collection $genres;

    public function __construct()
    {
        $this->likerPar = new ArrayCollection();
        $this->musiques = new ArrayCollection();
        $this->groupes = new ArrayCollection();
        $this->genres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getLikerPar(): Collection
    {
        return $this->likerPar;
    }

    public function addLikerPar(User $likerPar): static
    {
        if (!$this->likerPar->contains($likerPar)) {
            $this->likerPar->add($likerPar);
            $likerPar->addAlbumsLiker($this);
        }

        return $this;
    }

    public function removeLikerPar(User $likerPar): static
    {
        if ($this->likerPar->removeElement($likerPar)) {
            $likerPar->removeAlbumsLiker($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Musique>
     */
    public function getMusiques(): Collection
    {
        return $this->musiques;
    }

    public function addMusique(Musique $musique): static
    {
        if (!$this->musiques->contains($musique)) {
            $this->musiques->add($musique);
            $musique->setAlbum($this);
        }

        return $this;
    }

    public function removeMusique(Musique $musique): static
    {
        if ($this->musiques->removeElement($musique)) {
            // set the owning side to null (unless already changed)
            if ($musique->getAlbum() === $this) {
                $musique->setAlbum(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Groupe>
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): static
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes->add($groupe);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): static
    {
        $this->groupes->removeElement($groupe);

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): static
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): static
    {
        $this->genres->removeElement($genre);

        return $this;
    }
}

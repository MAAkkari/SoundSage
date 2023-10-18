<?php

namespace App\Entity;

use App\Repository\ArtisteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArtisteRepository::class)]
class Artiste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $info = null;

    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateMort = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'artistesLiker')]
    
    private Collection $likerPar;

    #[ORM\ManyToMany(targetEntity: Groupe::class, inversedBy: 'artistes')]
    private Collection $groupes;

    public function __construct()
    {
        $this->likerPar = new ArrayCollection();
        $this->groupes = new ArrayCollection();
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

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(?string $info): static
    {
        $this->info = $info;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getDateMort(): ?\DateTimeInterface
    {
        return $this->dateMort;
    }

    public function setDateMort(?\DateTimeInterface $dateMort): static
    {
        $this->dateMort = $dateMort;

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
            $likerPar->addArtistesLiker($this);
        }

        return $this;
    }

    public function removeLikerPar(User $likerPar): static
    {
        if ($this->likerPar->removeElement($likerPar)) {
            $likerPar->removeArtistesLiker($this);
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
}

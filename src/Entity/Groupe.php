<?php

namespace App\Entity;

use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupeRepository::class)]
class Groupe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateSeparation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $info = null;

    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    #[ORM\Column(length: 255)]
    private ?string $couverture = null;

    #[ORM\OneToMany(mappedBy: 'groupe', targetEntity: Post::class, orphanRemoval: true)]
    private Collection $posts;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'groupesLiker')]
    #[ORM\JoinTable(name: "user_groupe_liker")]
    private Collection $likerPar;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'groupesAdministrer')]
   
    private Collection $admins;

    #[ORM\ManyToMany(targetEntity: Artiste::class, mappedBy: 'groupes')]
    private Collection $artistes;

    #[ORM\ManyToMany(targetEntity: Album::class, mappedBy: 'groupes')]
    private Collection $albums;

    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'groupes')]
    private Collection $genre;

    #[ORM\ManyToMany(targetEntity: Musique::class, inversedBy: 'groupes')]
    private Collection $musiques;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->likerPar = new ArrayCollection();
        $this->admins = new ArrayCollection();
        $this->artistes = new ArrayCollection();
        $this->albums = new ArrayCollection();
        $this->genre = new ArrayCollection();
        $this->musiques = new ArrayCollection();
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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateSeparation(): ?\DateTimeInterface
    {
        return $this->dateSeparation;
    }

    public function setDateSeparation(?\DateTimeInterface $dateSeparation): static
    {
        $this->dateSeparation = $dateSeparation;

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

    public function getCouverture(): ?string
    {
        return $this->couverture;
    }

    public function setCouverture(string $couverture): static
    {
        $this->couverture = $couverture;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setGroupe($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getGroupe() === $this) {
                $post->setGroupe(null);
            }
        }

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
            $likerPar->addGroupesLiker($this);
        }

        return $this;
    }

    public function removeLikerPar(User $likerPar): static
    {
        if ($this->likerPar->removeElement($likerPar)) {
            $likerPar->removeGroupesLiker($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAdmins(): Collection
    {
        return $this->admins;
    }

    public function addAdmin(User $admin): static
    {
        if (!$this->admins->contains($admin)) {
            $this->admins->add($admin);
            $admin->addGroupesAdministrer($this);
        }

        return $this;
    }

    public function removeAdmin(User $admin): static
    {
        if ($this->admins->removeElement($admin)) {
            $admin->removeGroupesAdministrer($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Artiste>
     */
    public function getArtistes(): Collection
    {
        return $this->artistes;
    }

    public function addArtiste(Artiste $artiste): static
    {
        if (!$this->artistes->contains($artiste)) {
            $this->artistes->add($artiste);
            $artiste->addGroupe($this);
        }

        return $this;
    }

    public function removeArtiste(Artiste $artiste): static
    {
        if ($this->artistes->removeElement($artiste)) {
            $artiste->removeGroupe($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Album>
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function addAlbum(Album $album): static
    {
        if (!$this->albums->contains($album)) {
            $this->albums->add($album);
            $album->addGroupe($this);
        }

        return $this;
    }

    public function removeAlbum(Album $album): static
    {
        if ($this->albums->removeElement($album)) {
            $album->removeGroupe($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenre(): Collection
    {
        return $this->genre;
    }

    public function addGenre(Genre $genre): static
    {
        if (!$this->genre->contains($genre)) {
            $this->genre->add($genre);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): static
    {
        $this->genre->removeElement($genre);

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
        }

        return $this;
    }

    public function removeMusique(Musique $musique): static
    {
        $this->musiques->removeElement($musique);

        return $this;
    }
}

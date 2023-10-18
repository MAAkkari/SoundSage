<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $texte = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Commentaire::class, orphanRemoval: true)]
    private Collection $commentaires;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $auteur = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'postsLiker')]
    #[ORM\JoinTable(name: "user_post_liker")]
    private Collection $likerPar;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'postsDisliker')]
    private Collection $dislikerPar;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Groupe $groupe = null;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->likerPar = new ArrayCollection();
        $this->dislikerPar = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): static
    {
        $this->texte = $texte;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
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
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setPost($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getPost() === $this) {
                $commentaire->setPost(null);
            }
        }

        return $this;
    }

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): static
    {
        $this->auteur = $auteur;

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
            $likerPar->addPostsLiker($this);
        }

        return $this;
    }

    public function removeLikerPar(User $likerPar): static
    {
        if ($this->likerPar->removeElement($likerPar)) {
            $likerPar->removePostsLiker($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getDislikerPar(): Collection
    {
        return $this->dislikerPar;
    }

    public function addDislikerPar(User $dislikerPar): static
    {
        if (!$this->dislikerPar->contains($dislikerPar)) {
            $this->dislikerPar->add($dislikerPar);
            $dislikerPar->addPostsDisliker($this);
        }

        return $this;
    }

    public function removeDislikerPar(User $dislikerPar): static
    {
        if ($this->dislikerPar->removeElement($dislikerPar)) {
            $dislikerPar->removePostsDisliker($this);
        }

        return $this;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): static
    {
        $this->groupe = $groupe;

        return $this;
    }
}

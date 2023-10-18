<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $texte = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $auteur = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'commentairesLiker')]
    #[ORM\JoinTable(name: "user_commentaire_liker")]
    private Collection $likerPar;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'commentairesDisliker')]
    private Collection $dislikerPar;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $post = null;

    public function __construct()
    {
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
            $likerPar->addCommentairesLiker($this);
        }

        return $this;
    }

    public function removeLikerPar(User $likerPar): static
    {
        if ($this->likerPar->removeElement($likerPar)) {
            $likerPar->removeCommentairesLiker($this);
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
            $dislikerPar->addCommentairesDisliker($this);
        }

        return $this;
    }

    public function removeDislikerPar(User $dislikerPar): static
    {
        if ($this->dislikerPar->removeElement($dislikerPar)) {
            $dislikerPar->removeCommentairesDisliker($this);
        }

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        $this->post = $post;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\HistoriqueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoriqueRepository::class)]
class Historique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEcoute = null;

    #[ORM\ManyToOne(inversedBy: 'historiques')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'historiques')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Musique $musique = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEcoute(): ?\DateTimeInterface
    {
        return $this->dateEcoute;
    }

    public function setDateEcoute(\DateTimeInterface $dateEcoute): static
    {
        $this->dateEcoute = $dateEcoute;

        return $this;
    }

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?User $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getMusique(): ?Musique
    {
        return $this->musique;
    }

    public function setMusique(?Musique $musique): static
    {
        $this->musique = $musique;

        return $this;
    }
}

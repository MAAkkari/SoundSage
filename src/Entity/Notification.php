<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $route = null;

    #[ORM\Column]
    private ?int $idRedirect = null;

    #[ORM\Column(nullable: true)]
    private ?int $idOptionnel = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateNotif = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(string $route): static
    {
        $this->route = $route;

        return $this;
    }

    public function getIdRedirect(): ?int
    {
        return $this->idRedirect;
    }

    public function setIdRedirect(int $idRedirect): static
    {
        $this->idRedirect = $idRedirect;

        return $this;
    }

    public function getIdOptionnel(): ?int
    {
        return $this->idOptionnel;
    }

    public function setIdOptionnel(?int $idOptionnel): static
    {
        $this->idOptionnel = $idOptionnel;

        return $this;
    }

    public function getDateNotif(): ?\DateTimeInterface
    {
        return $this->dateNotif;
    }

    public function setDateNotif(\DateTimeInterface $dateNotif): static
    {
        $this->dateNotif = $dateNotif;

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
}

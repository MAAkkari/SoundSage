<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 25)]
    private ?string $pseudo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $premiumUntil = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $banUntil = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column]
    private ?bool $verified = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'bloquerPar')]
    private Collection $bloquer;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'bloquer')]
    private Collection $bloquerPar;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Notification::class, orphanRemoval: true)]
    private Collection $notifications;

    #[ORM\OneToMany(mappedBy: 'auteur', targetEntity: Commentaire::class, orphanRemoval: true)]
    private Collection $commentaires;

    #[ORM\ManyToMany(targetEntity: Commentaire::class, inversedBy: 'likerPar')]
    #[ORM\JoinTable(name: "user_commentaire_liker")]
    private Collection $commentairesLiker;

    #[ORM\ManyToMany(targetEntity: Commentaire::class, inversedBy: 'dislikerPar')]
    private Collection $commentairesDisliker;

    #[ORM\OneToMany(mappedBy: 'auteur', targetEntity: Post::class, orphanRemoval: true)]
    private Collection $posts;

    #[ORM\ManyToMany(targetEntity: Post::class, inversedBy: 'likerPar')]
    #[ORM\JoinTable(name: "user_post_liker")]
    private Collection $postsLiker;

    #[ORM\ManyToMany(targetEntity: Post::class, inversedBy: 'dislikerPar')]
    private Collection $postsDisliker;

    #[ORM\ManyToMany(targetEntity: Artiste::class, inversedBy: 'likerPar')]
    
    private Collection $artistesLiker;

    #[ORM\ManyToMany(targetEntity: Groupe::class, inversedBy: 'likerPar')]
    #[ORM\JoinTable(name: "user_groupe_liker")]
    private Collection $groupesLiker;

    #[ORM\ManyToMany(targetEntity: Groupe::class, inversedBy: 'admins')]
    
    private Collection $groupesAdministrer;

    #[ORM\ManyToMany(targetEntity: Album::class, inversedBy: 'likerPar')]
    private Collection $albumsLiker;

    #[ORM\OneToMany(mappedBy: 'auteur', targetEntity: Playlist::class, orphanRemoval: true)]
    private Collection $playlists;

    #[ORM\ManyToMany(targetEntity: Musique::class, inversedBy: 'likerPar')]
    private Collection $musiquesLiker;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Historique::class, orphanRemoval: true)]
    private Collection $historiques;

    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: Message::class)]
    private Collection $sentMessages;

    #[ORM\OneToMany(mappedBy: 'receiver', targetEntity: Message::class)]
    private Collection $receivedMessages;

    public function __construct()
    {
        $this->bloquer = new ArrayCollection();
        $this->bloquerPar = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->commentairesLiker = new ArrayCollection();
        $this->commentairesDisliker = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->postsLiker = new ArrayCollection();
        $this->postsDisliker = new ArrayCollection();
        $this->artistesLiker = new ArrayCollection();
        $this->groupesLiker = new ArrayCollection();
        $this->groupesAdministrer = new ArrayCollection();
        $this->albumsLiker = new ArrayCollection();
        $this->playlists = new ArrayCollection();
        $this->musiquesLiker = new ArrayCollection();
        $this->historiques = new ArrayCollection();
        $this->sentMessages = new ArrayCollection();
        $this->receivedMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function countUnreadMessagesFromUser(User $sender): int
    {
        $count = 0;
        foreach ($this->getReceivedMessages() as $message) {
            if (!$message->isIsRead() && $message->getSender() === $sender) {
                $count++;
            }
        }

        return $count;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getPremiumUntil(): ?\DateTimeInterface
    {
        return $this->premiumUntil;
    }

    public function setPremiumUntil(?\DateTimeInterface $premiumUntil): static
    {
        $this->premiumUntil = $premiumUntil;

        return $this;
    }

    public function getBanUntil(): ?\DateTimeInterface
    {
        return $this->banUntil;
    }

    public function setBanUntil(?\DateTimeInterface $banUntil): static
    {
        $this->banUntil = $banUntil;

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

    public function isVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): static
    {
        $this->verified = $verified;

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


    /**
     * @return Collection<int, self>
     */
    public function getBloquer(): Collection
    {
        return $this->bloquer;
    }

    public function addBloquer(self $bloquer): static
    {
        if (!$this->bloquer->contains($bloquer)) {
            $this->bloquer->add($bloquer);
        }

        return $this;
    }

    public function removeBloquer(self $bloquer): static
    {
        $this->bloquer->removeElement($bloquer);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getBloquerPar(): Collection
    {
        return $this->bloquerPar;
    }

    public function addBloquerPar(self $bloquerPar): static
    {
        if (!$this->bloquerPar->contains($bloquerPar)) {
            $this->bloquerPar->add($bloquerPar);
            $bloquerPar->addBloquer($this);
        }

        return $this;
    }

    public function removeBloquerPar(self $bloquerPar): static
    {
        if ($this->bloquerPar->removeElement($bloquerPar)) {
            $bloquerPar->removeBloquer($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setUtilisateur($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUtilisateur() === $this) {
                $notification->setUtilisateur(null);
            }
        }

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
            $commentaire->setAuteur($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getAuteur() === $this) {
                $commentaire->setAuteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentairesLiker(): Collection
    {
        return $this->commentairesLiker;
    }

    public function addCommentairesLiker(Commentaire $commentairesLiker): static
    {
        if (!$this->commentairesLiker->contains($commentairesLiker)) {
            $this->commentairesLiker->add($commentairesLiker);
        }

        return $this;
    }

    public function removeCommentairesLiker(Commentaire $commentairesLiker): static
    {
        $this->commentairesLiker->removeElement($commentairesLiker);

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentairesDisliker(): Collection
    {
        return $this->commentairesDisliker;
    }

    public function addCommentairesDisliker(Commentaire $commentairesDisliker): static
    {
        if (!$this->commentairesDisliker->contains($commentairesDisliker)) {
            $this->commentairesDisliker->add($commentairesDisliker);
        }

        return $this;
    }

    public function removeCommentairesDisliker(Commentaire $commentairesDisliker): static
    {
        $this->commentairesDisliker->removeElement($commentairesDisliker);

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
            $post->setAuteur($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuteur() === $this) {
                $post->setAuteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPostsLiker(): Collection
    {
        return $this->postsLiker;
    }

    public function addPostsLiker(Post $postsLiker): static
    {
        if (!$this->postsLiker->contains($postsLiker)) {
            $this->postsLiker->add($postsLiker);
        }

        return $this;
    }

    public function removePostsLiker(Post $postsLiker): static
    {
        $this->postsLiker->removeElement($postsLiker);

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPostsDisliker(): Collection
    {
        return $this->postsDisliker;
    }

    public function addPostsDisliker(Post $postsDisliker): static
    {
        if (!$this->postsDisliker->contains($postsDisliker)) {
            $this->postsDisliker->add($postsDisliker);
        }

        return $this;
    }

    public function removePostsDisliker(Post $postsDisliker): static
    {
        $this->postsDisliker->removeElement($postsDisliker);

        return $this;
    }

    /**
     * @return Collection<int, Artiste>
     */
    public function getArtistesLiker(): Collection
    {
        return $this->artistesLiker;
    }

    public function addArtistesLiker(Artiste $artistesLiker): static
    {
        if (!$this->artistesLiker->contains($artistesLiker)) {
            $this->artistesLiker->add($artistesLiker);
        }

        return $this;
    }

    public function removeArtistesLiker(Artiste $artistesLiker): static
    {
        $this->artistesLiker->removeElement($artistesLiker);

        return $this;
    }

    /**
     * @return Collection<int, Groupe>
     */
    public function getGroupesLiker(): Collection
    {
        return $this->groupesLiker;
    }

    public function addGroupesLiker(Groupe $groupesLiker): static
    {
        if (!$this->groupesLiker->contains($groupesLiker)) {
            $this->groupesLiker->add($groupesLiker);
        }

        return $this;
    }

    public function removeGroupesLiker(Groupe $groupesLiker): static
    {
        $this->groupesLiker->removeElement($groupesLiker);

        return $this;
    }

    /**
     * @return Collection<int, Groupe>
     */
    public function getGroupesAdministrer(): Collection
    {
        return $this->groupesAdministrer;
    }

    public function addGroupesAdministrer(Groupe $groupesAdministrer): static
    {
        if (!$this->groupesAdministrer->contains($groupesAdministrer)) {
            $this->groupesAdministrer->add($groupesAdministrer);
        }

        return $this;
    }

    public function removeGroupesAdministrer(Groupe $groupesAdministrer): static
    {
        $this->groupesAdministrer->removeElement($groupesAdministrer);

        return $this;
    }

    /**
     * @return Collection<int, Album>
     */
    public function getAlbumsLiker(): Collection
    {
        return $this->albumsLiker;
    }

    public function addAlbumsLiker(Album $albumsLiker): static
    {
        if (!$this->albumsLiker->contains($albumsLiker)) {
            $this->albumsLiker->add($albumsLiker);
        }

        return $this;
    }

    public function removeAlbumsLiker(Album $albumsLiker): static
    {
        $this->albumsLiker->removeElement($albumsLiker);

        return $this;
    }

    /**
     * @return Collection<int, Playlist>
     */
    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function addPlaylist(Playlist $playlist): static
    {
        if (!$this->playlists->contains($playlist)) {
            $this->playlists->add($playlist);
            $playlist->setAuteur($this);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): static
    {
        if ($this->playlists->removeElement($playlist)) {
            // set the owning side to null (unless already changed)
            if ($playlist->getAuteur() === $this) {
                $playlist->setAuteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Musique>
     */
    public function getMusiquesLiker(): Collection
    {
        return $this->musiquesLiker;
    }

    public function addMusiquesLiker(Musique $musiquesLiker): static
    {
        if (!$this->musiquesLiker->contains($musiquesLiker)) {
            $this->musiquesLiker->add($musiquesLiker);
        }

        return $this;
    }

    public function removeMusiquesLiker(Musique $musiquesLiker): static
    {
        $this->musiquesLiker->removeElement($musiquesLiker);

        return $this;
    }

    /**
     * @return Collection<int, Historique>
     */
    public function getHistoriques(): Collection
    {
        return $this->historiques;
    }

    public function addHistorique(Historique $historique): static
    {
        if (!$this->historiques->contains($historique)) {
            $this->historiques->add($historique);
            $historique->setUtilisateur($this);
        }

        return $this;
    }

    public function removeHistorique(Historique $historique): static
    {
        if ($this->historiques->removeElement($historique)) {
            // set the owning side to null (unless already changed)
            if ($historique->getUtilisateur() === $this) {
                $historique->setUtilisateur(null);
            }
        }

        return $this;
    }
    public function __toString(){
        return $this->email;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getSentMessages(): Collection
    {
        return $this->sentMessages;
    }

    public function addSentMessage(Message $sentMessage): static
    {
        if (!$this->sentMessages->contains($sentMessage)) {
            $this->sentMessages->add($sentMessage);
            $sentMessage->setSender($this);
        }

        return $this;
    }

    public function removeSentMessage(Message $sentMessage): static
    {
        if ($this->sentMessages->removeElement($sentMessage)) {
            // set the owning side to null (unless already changed)
            if ($sentMessage->getSender() === $this) {
                $sentMessage->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getReceivedMessages(): Collection
    {
        return $this->receivedMessages;
    }

    public function addReceivedMessage(Message $receivedMessage): static
    {
        if (!$this->receivedMessages->contains($receivedMessage)) {
            $this->receivedMessages->add($receivedMessage);
            $receivedMessage->setReceiver($this);
        }

        return $this;
    }

    public function removeReceivedMessage(Message $receivedMessage): static
    {
        if ($this->receivedMessages->removeElement($receivedMessage)) {
            // set the owning side to null (unless already changed)
            if ($receivedMessage->getReceiver() === $this) {
                $receivedMessage->setReceiver(null);
            }
        }

        return $this;
    }
}

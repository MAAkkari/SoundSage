<?php
namespace App\components;

use App\Entity\User;
use App\Entity\Album;
use App\Entity\Musique;
use App\Entity\Playlist;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\LiveComponentInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsLiveComponent]
class NewPlaylistComponent extends AbstractController
{
    use DefaultActionTrait;

    private EntityManagerInterface $entityManager;
    private Security $security;

    #[LiveProp(writable: true)]
    public ?string $playlistName = null;

    #[LiveProp]
    public ?int $musiqueId = null;  // Optional: ID of a single musique

    #[LiveProp]
    public ?int $albumId = null;    // Optional: ID of an album
    
    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    #[LiveAction]
    public function handlePlaylist()
    {
        $user = $this->security->getUser();
        if (!$user) {
            $this->addFlash("error", "Vous devez être connecté");
            return;
        }
        if(!$this->playlistName){
            $this->addFlash("error", "Vous devez donner un nom à votre playlist");
            return;
        }

        $playlist = $this->entityManager->getRepository(Playlist::class)->findOneBy(['nom' => $this->playlistName, 'auteur' => $user]);

        if (!$playlist) {
            $playlist = new Playlist();
            $playlist->setNom($this->playlistName);
            $playlist->setAuteur($user);
            $playlist->setPublic(true);
            $playlist->setImage('defaultTrack.jpg');
            $this->entityManager->persist($playlist);
        }

        if ($this->musiqueId) {
            $this->addMusiqueToPlaylist($this->musiqueId, $playlist);
        }

        if ($this->albumId) {
            $this->addAlbumToPlaylist($this->albumId, $playlist);
        }

        $this->entityManager->flush();
    }

    private function addMusiqueToPlaylist(int $musiqueId, Playlist $playlist)
    {
        $musique = $this->entityManager->getRepository(Musique::class)->find($musiqueId);
        if ($musique && !$playlist->getMusiques()->contains($musique)) {
            $playlist->addMusique($musique);
        }
    }

    private function addAlbumToPlaylist(int $albumId, Playlist $playlist)
    {
        $album = $this->entityManager->getRepository(Album::class)->find($albumId);
        if ($album) {
            foreach ($album->getMusiques() as $musique) {
                if (!$playlist->getMusiques()->contains($musique)) {
                    $playlist->addMusique($musique);
                }
            }
        }
    }
}

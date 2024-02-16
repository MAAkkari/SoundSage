<?php 
namespace App\components;

use App\Entity\Playlist;
use App\Entity\Musique;
use App\Entity\Album;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsLiveComponent]
class AddToPlaylistComponent extends AbstractController
{
    use DefaultActionTrait;

    private EntityManagerInterface $entityManager;
    private Security $security;

    #[LiveProp]
    public ?int $musiqueId = null;  // Optional: ID of a single musique

    #[LiveProp]
    public ?int $albumId = null;    // Optional: ID of an album

    #[LiveProp]
    public ?string $playlistName = null;

    
    public ?string $status = null;
    public ?string $message = null;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    #[LiveAction]
    public function addToPlaylist()
    {
        $user = $this->security->getUser();
        if (!$user) {
            $this->addFlash("error", "Vous devez être connecté");
            return;
        }

        $playlist = $this->entityManager->getRepository(Playlist::class)->findOneBy(['nom' => $this->playlistName, 'auteur' => $user]);
        if (!$playlist) {
            $this->addFlash("error", "Playlist introuvable");
            return;
        }

        if ($this->musiqueId !== null) {
            $this->addMusiqueToPlaylist($this->musiqueId, $playlist);
        } elseif ($this->albumId !== null) {
            $this->addAlbumToPlaylist($this->albumId, $playlist);
        }
    }

    private function addMusiqueToPlaylist(int $musiqueId, Playlist $playlist)
    {
        $musique = $this->entityManager->getRepository(Musique::class)->find($musiqueId);
        if (!$musique) {
            $this->addFlash("error", "Musique introuvable");
            return;
        }

        if (!$playlist->getMusiques()->contains($musique)) {
            $playlist->addMusique($musique);
            $this->entityManager->flush();
            $this->status = "success";
            $this->message = "Musique ajoutée à la playlist";
        } else {
            $this->status = "error";
            $this->message = "Musique déjà présente dans la playlist";
        }
    }

    private function addAlbumToPlaylist(int $albumId, Playlist $playlist)
    {
        $album = $this->entityManager->getRepository(Album::class)->find($albumId);
        if (!$album) {
            $this->addFlash("error", "Album introuvable");
            return;
        }

        foreach ($album->getMusiques() as $musique) {
            if (!$playlist->getMusiques()->contains($musique)) {
                $playlist->addMusique($musique);
            }
        }
        $this->entityManager->flush();
        $this->addFlash("success", "Album ajouté à la playlist");
    }
}

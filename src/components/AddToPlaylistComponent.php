<?php
namespace App\components;

use App\Entity\Musique;
use App\Entity\Album;
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
class AddToPlaylistComponent extends AbstractController
{
    use DefaultActionTrait;

    private EntityManagerInterface $entityManager;
    private Security $security;

    #[LiveProp]
    public ?string $entityType = null; // 'musique' ou 'album'

    #[LiveProp]
    public ?int $entityId = null; // id de la musique ou de l'album

    #[LiveProp]
    public ?string $playlistName = null;

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

        if ($this->entityType === 'musique') {
            $this->addMusiqueToPlaylist($this->entityId, $playlist);
        } elseif ($this->entityType === 'album') {
            $album = $this->entityManager->getRepository(Album::class)->find($this->entityId);
            if (!$album) {
                $this->addFlash("error", "Album introuvable");
                return;
            }

            foreach ($album->getMusiques() as $musique) {
                $this->addMusiqueToPlaylist($musique->getId(), $playlist);
            }
        }
    }

    private function addMusiqueToPlaylist(int $musiqueId, Playlist $playlist)
    {
        $musique = $this->entityManager->getRepository(Musique::class)->find($musiqueId);
        if (!$musique) {
            $this->addFlash("error", "Musique introuvable");
            return;
        }

        if ($playlist->getMusiques()->contains($musique)) {
            // Musique est déjà dans la playlist
            $this->addFlash("error", "La chanson est déjà dans la playlist " . $playlist->getNom());
        } else {
            // Musique n'est pas dans la playlist
            $playlist->addMusique($musique);
            $this->entityManager->persist($playlist);
            $this->entityManager->flush();
            $this->addFlash("success", "Ajout de la chanson à la playlist " . $playlist->getNom() . " réussi");
        }
    }
}

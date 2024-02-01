<?php 
namespace App\components;

use App\Entity\Post;
use App\Entity\Album;
use App\Entity\Genre;
use App\Entity\Groupe;
use App\Entity\Musique;
use App\Entity\Playlist;
use App\Entity\Commentaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsLiveComponent('SearchListGenreComponent')]
class SearchListGenreComponent extends AbstractController
{
    use DefaultActionTrait;
    private EntityManagerInterface $entityManager;

    #[LiveProp(writable: true)]
    public string $query = '';

    #[LiveProp]
    public ?string $searchType = null;

    
    #[LiveProp]
    public array $items = [];

public function __construct(EntityManagerInterface $entityManager)
{
    $this->entityManager = $entityManager;
    $entites = $this->entityManager->getRepository(Genre::class)->findAll();

    foreach ($entites as $entite) {
        $this->items[] = [
            'id' => $entite->getId(),
            'nom' => $entite->getNom(),
            'photo' => $entite->getImage(),
        ];
    }
}

#[LiveAction]
public function getSearchResults(): array
{
    if ($this->query === '') {
        return $this->items;
    }

    return array_filter($this->items, function ($item) {
        return stripos($item['nom'], $this->query) !== false;
    });
}}
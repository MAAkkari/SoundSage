<?php 
namespace App\components;

use App\Entity\Post;
use App\Entity\Album;
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

#[AsLiveComponent('SearchListComponent')]
class SearchListComponent extends AbstractController
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
    $entities = $this->entityManager->getRepository(Groupe::class)->findAll();

    foreach ($entities as $entity) {
        $this->items[] = [
            'id' => $entity->getId(),
            'nom' => $entity->getNom(),
            'photo' => $entity->getPhoto(), // replace with your method to get the picture
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
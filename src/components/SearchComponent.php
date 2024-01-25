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

#[AsLiveComponent('SearchComponent')]
class SearchComponent extends AbstractController
{
    use DefaultActionTrait;
    private EntityManagerInterface $entityManager;

    #[LiveProp(writable: true)]
    public string $query = '';

    #[LiveProp]
    public ?string $searchType = null;

   
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[LiveAction]
    public function getSearchResults(): array
    {
        $results = [];

        if ($this->query !== '') {
            if ($this->searchType === 'all' || $this->searchType === 'groupe') {
                $results['groupes'] = $this->searchEntity(Groupe::class, $this->query);
            }
            if ($this->searchType === 'all' || $this->searchType === 'Musique') {
                $results['musiques'] = $this->searchEntity(Musique::class, $this->query);
            }
            if ($this->searchType === 'all' || $this->searchType === 'album') {
                $results['albums'] = $this->searchEntity(Album::class, $this->query);
            }
        }

        return $results;
    }

    private function searchEntity(string $entityClass, string $searchTerm): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('entity')
                     ->from($entityClass, 'entity')
                     ->where('entity.nom LIKE :term')
                     ->setParameter('term', '%' . $searchTerm . '%');

        return $queryBuilder->getQuery()->getResult();
    }
}
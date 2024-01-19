<?php 
namespace App\components;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Album;
use App\Entity\Groupe;
use App\Entity\Musique;
use App\Entity\Commentaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent]
class LikeCounterComponent
{
    use DefaultActionTrait;
    private EntityManagerInterface $entityManager;
    private Security $security;

    #[LiveProp]
    public ?string $entityType=null; 

    #[LiveProp]
    public ?int $entityId=null;
    
    #[LiveProp]
    public bool $isLiked = false;

    #[LiveProp]
    public ?int $nbLike=0;
    

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {   
        $this->entityManager = $entityManager;
        $this->security = $security;   
    }

    public function mount()
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return;
        }

        $entity = $this->getEntity();
        if (!$entity) {
            return;
        }
        $this->isLiked = in_array($user, $entity->getLikerPar()->toArray(), true);
    }
    
    #[LiveAction]
    public function toggleLike()
    {
        
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return;
        }
        
        $entity = $this->getEntity();
        if (!$entity) {
            return;
        }
        $this->mount();
        if ($this->isLiked) {
            $entity->removeLikerPar($user);
        } else {
            $entity->addLikerPar($user);
        }
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        $this->nbLike = $entity->getLikerPar()->count();
        $this->isLiked = !$this->isLiked;
    }

    private function getEntity()
    {
        switch ($this->entityType) {
            case 'Album':
                return $this->entityManager->getRepository(Album::class)->find($this->entityId);
            case 'Groupe':
                return $this->entityManager->getRepository(Groupe::class)->find($this->entityId);
            case 'Post':
                return $this->entityManager->getRepository(Post::class)->find($this->entityId);
            case 'Musique':
                return $this->entityManager->getRepository(Musique::class)->find($this->entityId);
            case 'Commentaire':
                return $this->entityManager->getRepository(Commentaire::class)->find($this->entityId);
            default:
                return null;
        }
    }
}
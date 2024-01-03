<?php

namespace App\Controller;

use App\Entity\Groupe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LikeController extends AbstractController
{
    #[Route('/like/groupe/{id}', name: 'like.groupe')]
    public function likeGroupe( Groupe $groupe, EntityManagerInterface $em ): Response
    {
        $user = $this->getUser();
        $users =[];
        foreach($groupe->getLikerPar() as $userLike){
            $users[]=$userLike;
        }
        if (in_array($user,$users)) {
            $groupe->removeLikerPar($user);
            $em->persist($groupe);
            $em->flush();
            return $this->json(['message'=>'Le like a ete supprimé.']);
        } else {
            $groupe->addLikerPar($user);
            $em->persist($groupe);
            $em->flush();
            return $this->json(['message'=>'Le like a ete ajouté.']);
        }
    }

        

        
}

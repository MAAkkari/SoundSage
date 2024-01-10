<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Album;
use App\Entity\Groupe;
use App\Entity\Musique;
use App\Entity\Commentaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LikeController extends AbstractController
{
    #[Route('/toggle-like/{type}/{id}', name: 'toggle_like')]
    public function toggleLike(string $type, int $id, EntityManagerInterface $em ){
        $user = $this->getUser();
        if($type === 'groupe'){
            $groupe = $em->getRepository(Groupe::class)->find($id);
            $users =[];
            foreach($groupe->getLikerPar() as $userLike){
                $users[]=$userLike;
            }
            if (in_array($user,$users)) {
                $groupe->removeLikerPar($user);
                $em->persist($groupe);
                $em->flush();
                
            } else {
                $groupe->addLikerPar($user);
                $em->persist($groupe);
                $em->flush();
                
            }
        }
        if($type === 'post'){
            $post = $em->getRepository(Post::class)->find($id);
            $users =[];
            foreach($post->getLikerPar() as $userLike){
                $users[]=$userLike;
            }
            if (in_array($user,$users)) {
                $post->removeLikerPar($user);
                $em->persist($post);
                $em->flush();
                
            } else {
                $post->addLikerPar($user);
                $em->persist($post);
                $em->flush();
                
            }
        }
        if($type === 'commentaire'){
            $commentaire = $em->getRepository(Commentaire::class)->find($id);
            $users =[];
            foreach($commentaire->getLikerPar() as $userLike){
                $users[]=$userLike;
            }
            if (in_array($user,$users)) {
                $commentaire->removeLikerPar($user);
                $em->persist($commentaire);
                $em->flush();
                
            } else {
                $commentaire->addLikerPar($user);
                $em->persist($commentaire);
                $em->flush();
                
            }
        }
        if($type === 'album'){
            $album = $em->getRepository(Album::class)->find($id);
            $users =[];
            foreach($album->getLikerPar() as $userLike){
                $users[]=$userLike;
            }
            if (in_array($user,$users)) {
                $album->removeLikerPar($user);
                $em->persist($album);
                $em->flush();
                
            } else {
                $album->addLikerPar($user);
                $em->persist($album);
                $em->flush();
                
            }
        }
        if($type === 'musique'){
            $musique = $em->getRepository(Musique::class)->find($id);
            $users =[];
            foreach($musique->getLikerPar() as $userLike){
                $users[]=$userLike;
            }
            if (in_array($user,$users)) {
                $musique->removeLikerPar($user);
                $em->persist($musique);
                $em->flush();
                
            } else {
                $musique->addLikerPar($user);
                $em->persist($musique);
                $em->flush();
            }
        }
        
        
    }



   

        

        
}

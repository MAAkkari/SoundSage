<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Groupe;
use App\Form\PostType;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\GroupeRepository;
use App\Repository\MusiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeController extends AbstractController
{
    #[Route('/groupe', name: 'app_groupe')]
    public function index(GroupeRepository $gr): Response
    {
        $groupesPopulaires = $gr->findPlusLiker(6);
        $groupes = $gr->findAll();

        $populaires=[];
        foreach($groupesPopulaires as $groupe){
            $populaires[]= $gr->find($groupe['groupe_id']);
        }


        return $this->render('groupe/index.html.twig', [
            'populaires'=> $populaires , 
            'groupes'=> $groupes
        ]);
    }
    #[Route('/page', name: 'app_page')]
    public function page(GroupeRepository $gr): Response {
        $user = $this->getUser();
        $groupesPopulaires = $gr->findPlusLiker(4);
        $groupes = $gr->findAll();
        $populaires=[];
        foreach($groupesPopulaires as $groupe){
            $populaires[]= $gr->find($groupe['groupe_id']);
        }


        return $this->render('groupe/page.html.twig', [
            'populaires'=> $populaires , 
            'groupes'=> $groupes,
            'user'=>$user
        ]);
    }
    #[Route('/page/{id}', name: 'show_page')]
    public function showPage( Groupe $groupe , GroupeRepository $gr, MusiqueRepository $mr, Request $request , EntityManagerInterface $entityManager ): Response{
        $user = $this->getUser();
        $post = new Post();
        $formPost = $this->createForm(PostType::class, $post);
        $formPost->handleRequest($request);
            if($formPost->isSubmitted() && $formPost->isValid()){
                $post = $formPost->getData();
                $post->setAuteur($user);
                $post->setGroupe($groupe);
                $post->setDateCreation(new \DateTime());
                $entityManager->persist($post); 
                $entityManager->flush();
                $this->addFlash("success","creation du type de post avec succés");
            }



            $commentForms = [];
            foreach ($groupe->getPosts() as $post) {
                $comment = new Commentaire();
                $commentForm = $this->createForm(CommentaireType::class, $comment, [
                    'action' => $this->generateUrl('comment_post', ['postId' => $post->getId()]),
                    'method' => 'POST',
                ]);
                $commentForms[$post->getId()] = $commentForm->createView();
            }




            

        $TopMusique = $gr->findPlusEcouter($groupe->getId());
        foreach($TopMusique as $musique){
            $musiquesPopulaires[]= $mr->find($musique['id']);
        }
        
        return $this->render('groupe/showPage.html.twig', [
            'groupe'=> $groupe,
            'user'=>$user,
            'musiquesPopulaires'=>$musiquesPopulaires,
            'formAddPost'=>$formPost->createView(),
            'commentForms' => $commentForms,
        ]);
    }
}
   

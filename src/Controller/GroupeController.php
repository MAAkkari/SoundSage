<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Repository\GroupeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeController extends AbstractController
{
    #[Route('/groupe', name: 'app_groupe')]
    public function index(GroupeRepository $gr): Response
    {
        $groupesPopulaires = $gr->findPlusLiker();
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
        $groupesPopulaires = $gr->findPlusLiker();
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
    public function showPage( Groupe $groupe , GroupeRepository $gr): Response{
        $user = $this->getUser();
        return $this->render('groupe/showPage.html.twig', [
            'groupe'=> $groupe,
            'user'=>$user
        ]);

    }
}

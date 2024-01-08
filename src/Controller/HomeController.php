<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\GroupeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(AlbumRepository $ar, GroupeRepository $gr): Response
    {  
        //affiche les 10 derniers albums selon la date de sortie 
        $albums = $ar->findBy(
            [],
            ['dateCreation' => 'DESC'],
            10 
        );

        //utilise la fonction définie dans le repository pour recuperer les id des 6 groupes avec le plus d'ecoutes en additionant les ecoutes de 
        //toutes leurs musiques, puis recupere les objets avec les ids
        $GroupesPopulaires = $gr->findPopulaire();
        $groupes=[];
        foreach($GroupesPopulaires as $groupe){
            $groupes[]= $gr->find($groupe['id']);
        }

        return $this->render('home/index.html.twig', [
            'albums'=>$albums,
            'groupes'=>$groupes
        ]);
    }
}

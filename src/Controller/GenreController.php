<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use App\Repository\GroupeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GenreController extends AbstractController
{
    #[Route('/genre', name: 'app_genre')]
    public function index(GenreRepository $gr): Response
    {
         // compte le nombre total de musique de chaque genre et affiche le genre avec le plus de musiques
        $populairesGenres = $gr->findPopulaire();
        $genres = $gr->findAll();
        $populaires=[];
        foreach($populairesGenres as $genre){
            $populaires[]= $gr->find($genre['genre_id']);
        }
        
        return $this->render('genre/index.html.twig', [
            'populaires' =>$populaires , 
            'genres' => $genres
        ]);
    }
}

<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\GenreRepository;
use App\Repository\GroupeRepository;
use App\Repository\MusiqueRepository;
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

    #[Route('/genre/{id}', name: 'show_genre')]
    public function show(GenreRepository $gr, GroupeRepository $gr2,AlbumRepository $ar, MusiqueRepository $mr , $id): Response
    {
        $genre = $gr->find($id);
        $groupes = $genre->getGroupes();
        $albums = $genre->getAlbums();
        $musiques = $genre->getMusiques();
        

        return $this->render('genre/show.html.twig', [
            'genre' => $genre,
            'groupes' => $groupes,
            'albums' => $albums,
            'musiques' => $musiques
        ]);
    }
}



<?php

namespace App\Controller;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AlbumController extends AbstractController
{
    #[Route('/album', name: 'app_album')]
    public function index(AlbumRepository $ar): Response
    {
        $populaires = $ar->findBy(
            [],
            ['dateCreation' => 'DESC'],
            10 
        );
        return $this->render('album/index.html.twig', [
            'populaires'=>$populaires,
            'albums'=>$ar->findAll()
        ]);
    }

    #[Route('/album/{id}', name: 'show_album')]
    public function show(Album $album , AlbumRepository $ar): Response
    {
        return $this->render('album/show.html.twig', [
            'album'=>$ar->find($album->getId()),
        ]);
    }
    

}

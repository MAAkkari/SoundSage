<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\security;

class PlaylistController extends AbstractController
{
    #[Route('/playlist', name: 'app_playlist')]
    public function index(PlaylistRepository $pr): Response
    
    { $user = $this->getUser();
        if (!$user) {
            $this->addflash('danger', 'Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }
        $playlists = $pr->findBy(['auteur' => $user]);
        return $this->render('playlist/index.html.twig', [
            'playlists' => $playlists,
            'user'=>$user
        ]);
    }
    #[Route('/playlist/{id}/delete', name: 'delete_playlist')]
    public function delete(Playlist $playlist , EntityManagerInterface $em){   
        $em->remove($playlist);
        $em->flush();
        $this->addFlash("success","suppression de la playlist avec succes");
        return $this->redirectToRoute('app_playlist');
    }
}

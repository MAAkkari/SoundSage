<?php

namespace App\Controller;

use App\Entity\Musique;
use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
        return $this->redirectToRoute('app_playlist', [
            'message' => 'Suppression de la playlist avec succes',
            'success' => true,
        ]);
    }

    #[Route('/playlist/{id}/edit', name: 'edit_playlist')]
    public function edit(Playlist $playlist , EntityManagerInterface $em, Request $request){   
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $imagePlaylist = $form['image']->getData();
            if ($imagePlaylist){
                $originalFilename = pathinfo($imagePlaylist->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imagePlaylist->guessExtension();
                try {
                    $imagePlaylist->move(
                        $this->getParameter('playlists_uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash("error","erreur lors de l'upload de l'image");
                }
                $playlist->setImage($newFilename);
            }
            $playlist = $form->getData();
            $em->persist($playlist);
            $em->flush();
            return $this->redirectToRoute('app_playlist', [
                'message' => 'Modification de la playlist avec succes',
                'success' => true,
            ]);
        }
        
        return $this->render('playlist/edit.html.twig', [
            'form' => $form->createView(),
            'playlist'=>$playlist,
        ]);
    }
    
    #[Route('/playlist/{playlist_id}/delete/{music_id}', name: 'delete_from_playlist')]
    public function deleteFromPlaylist(Playlist $playlist_id, Musique $music_id, EntityManagerInterface $em){   
        $playlist_id->removeMusique($music_id);
        $id1= $playlist_id->getId();
        $em->flush();
        $this->addFlash("success","suppression de la musique avec succes");
        return $this->redirectToRoute('show_playlist', ['id' => $id1]);
    }

    #[Route('/playlist/{id}', name: 'show_playlist')]
    public function show(Playlist $playlist , PlaylistRepository $pr): Response
    {   
        $user = $this->getUser();
        //verifie si l'utilisateur est connecté
        if (!$user) {
            $this->addflash('danger', 'Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }
        //verifie si la playlist est publique ou si l'utilisateur est l'auteur de la playlist
        if ( $playlist->isPublic() == false && $playlist->getAuteur() != $user){
            $this->addflash('danger', 'Vous n\'avez pas accès à cette playlist');
            return $this->redirectToRoute('app_playlist');
        }
        return $this->render('playlist/show.html.twig', [
            'playlist'=>$pr->find($playlist->getId()),
            'user'=>$user
        ]);
    }
}
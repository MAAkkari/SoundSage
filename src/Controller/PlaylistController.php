<?php

namespace App\Controller;

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
        $this->addFlash("success","suppression de la playlist avec succes");
        return $this->redirectToRoute('app_playlist');
    }

    #[Route('/playlist/new', name: 'new_playlist')]
    #[Route('/playlist/{id}/edit', name: 'edit_playlist')] // si on veux rajouter l'edition 
    public function new_edit(Playlist $playlist =null ,Request $request , EntityManagerInterface $entityManager, string $playlistsUploadsDirectory):Response {
    $user = $this->getUser();
	if(!$playlist){$playlist = new Playlist();} //////////// crée une nouvelle playlist ( un objet pas dans la bdd )  // si on veux rajouter l'edition sinon on enleve le if mais on garde l'interrieur
        
        $form = $this->createForm(PlaylistType::class, $playlist); ////// ( crée le formulaire )

 	$form->handleRequest($request);
    
        if($form->isSubmitted() && $form->isValid() ){ // si le form est submit et qu'il est valide
            $playlist = $form->getData(); //on met les info dans l'entité playlist crée plus haut
            $file = $form->get('image')->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
    
                try {
                    $file->move($playlistsUploadsDirectory, $newFilename);
                    $playlist->setImage($newFilename); // Save the filename to your entity, if necessary
                } catch (FileException $e) {
                    $this->addflash('error', 'Une erreur est survenue lors de l\'upload de l\'image');
                }
            }
            $playlist->setAuteur($user); // on met l'auteur de la playlist
            $entityManager->persist($playlist); // prepare en pdo
            $entityManager->flush(); // execute en pdo

            $referer = $request->headers->get('referer');
            return $this->redirect($referer ?: $this->generateUrl('default_route'));
        }


        return $this->render('playlist/new.html.twig',[  //////// ( envoie du form dans la vue )
            'formAddPlaylist'=> $form ,
        ]);
   }
}

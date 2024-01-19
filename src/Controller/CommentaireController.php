<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CommentaireController extends AbstractController
{
    #[Route('/comment-post/{postId}', name: 'comment_post', methods: ['POST'])]
    public function commentPost($postId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = $entityManager->getRepository(Post::class)->find($postId);
        if (!$post) {
            throw $this->createNotFoundException('No post found for id '.$postId);
        }

        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setPost($post);
            $commentaire->setAuteur($this->getUser()); // Assuming you have a user logged in
            $commentaire->setDateCreation(new \DateTime());

            $entityManager->persist($commentaire);
            $entityManager->flush();

            $this->addFlash('success', 'Your comment was posted successfully.');

            // Redirect back to the page where the form was submitted
            return $this->redirect($request->headers->get('referer'));
        }

        // Handle the case where the form is not valid
        // You might want to add flash messages or log errors as needed

        // Redirect back to the form page, possibly with error messages
        return $this->redirect($request->headers->get('referer'));
    }
}

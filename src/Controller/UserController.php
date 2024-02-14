<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserController extends AbstractController
{
    #[Route('/favoris', name: 'app_favoris')]
    public function favoris(UserRepository $user): Response
    {
        $user = $this->getUser();
        return $this->render('user/favoris.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/profile', name: 'app_profil')]
    public function profil(UserRepository $user): Response
    {
        $user = $this->getUser();
        return $this->render('user/profil.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/user/edit', name: 'app_edit')]
    public function editProfil(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    
        $user = $this->getUser();
        $originalImage = $user->getImage();
    
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('image')->getData();
    
            if($file == null){
                $user->setImage($originalImage);
            } else {
                if ($file instanceof UploadedFile) {
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    // Sanitize the filename
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    // Append a unique identifier and the file extension to the filename
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
    
                    try {
                        // Move the file to the directory where user images are stored
                        $file->move(
                            $this->getParameter('users_uploads_directory'), // Define this parameter in services.yaml
                            $newFilename
                        );
                        
                        // Update the 'image' property to store the new image file name
                        $user->setImage($newFilename);
                    } catch (FileException $e) {
                        $this->addFlash('error', 'erreur lors de l\'upload de l\'image.');
                        return $this->render('registration/register.html.twig', [
                            'registrationForm' => $form->createView(),
                        ]);
                    }
                }
    
                // Handle password and other fields...
    
                $em->persist($user);
                $em->flush();
    
                return $this->redirectToRoute('app_home');
            }
        }
    
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/user/delete', name: 'app_delete')]
    public function deleteUser(EntityManagerInterface $em): Response
    {   if ($this->getUser() === null) {
        return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('app_home');
    }

}

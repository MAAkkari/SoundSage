<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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
        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/user/edit', name: 'app_edit_user')]
    public function editProfil(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em): Response {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $currentImage = $user->getImage();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setVerified(0);
            $user->setRoles(['ROLE_USER']);
            if($form->get('image')){
                $file = $form->get('image')->getData();
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('users_uploads_directory'), $fileName);
                $user->setImage($fileName);
            }
            else{
                $user->setImage($currentImage);
            }
            $em->persist($user);
            $em->flush();
           
        }
        return $this->render('user/edit.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/user/delete', name: 'app_delete_user')]
    public function deleteUser(EntityManagerInterface $em): Response
    {   if ($this->getUser() === null) {
        return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('app_login');
    }

    #[Route('/profile/{id}', name: 'app_user_profil')]
    public function UserProfile(User $user , UserRepository $ur): Response
    {   
        
        return $this->render('user/userProfile.html.twig', [
            'user' => $user,
        ]);
    }

}

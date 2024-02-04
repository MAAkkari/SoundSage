<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    #[Route('/profil', name: 'app_profil')]
    public function profil(UserRepository $user): Response
    {
        $user = $this->getUser();
        return $this->render('user/profil.html.twig', [
            'user' => $user,
        ]);
    }
}

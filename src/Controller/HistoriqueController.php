<?php

namespace App\Controller;

use App\Entity\Musique;
use App\Entity\Historique;
use App\Repository\HistoriqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HistoriqueController extends AbstractController
{

    
    #[Route('/historique', name: 'app_historique')]
    public function userHistorique(HistoriqueRepository $historiqueRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            // Redirect to login page or handle unauthorized access
            return $this->redirectToRoute('app_login');
        }

        $historiques = $historiqueRepository->findBy(['utilisateur' => $user]);

        return $this->render('historique/index.html.twig', [
            'historiques' => $historiques,
        ]);
    }
    
    #[Route('/add-to-history', name: 'add_to_history', methods: ['POST'])]
    public function addToHistory(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userId = $this->getUser()->getId();
        $musiqueId = $request->request->get('musiqueId');

        $musique = $entityManager->getRepository(Musique::class)->find($musiqueId);

        if ($musique) {
            $historique = new Historique();
            $historique->setUtilisateur($this->getUser());
            $historique->setMusique($musique);
            $historique->setDateEcoute(new \DateTime());

            $entityManager->persist($historique);
            $entityManager->flush();
        }

        return new Response(null, Response::HTTP_OK);
    }
}

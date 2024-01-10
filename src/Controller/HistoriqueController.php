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
    public function index(HistoriqueRepository $historiqueRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            // Handle not logged in user
            throw $this->createAccessDeniedException('Connectez-vous pour accéder a votre historique.');
        }

        $historiques = $historiqueRepository->findBy(
            ['utilisateur' => $user],
            ['dateEcoute' => 'DESC']
        );

        $groupedHistoriques = [];
        $aujourdhui = new \DateTime('today');
        $hier = new \DateTime('yesterday');

        foreach ($historiques as $historique) {
            $dateEcoute = $historique->getDateEcoute();
            $formattedDate = $dateEcoute->format('Y-m-d');

            if ($dateEcoute >= $aujourdhui) {
                $groupedHistoriques['aujourdhui'][] = $historique;
            } elseif ($dateEcoute >= $hier) {
                $groupedHistoriques['hier'][] = $historique;
            } else {
                $groupedHistoriques[$formattedDate][] = $historique;
            }
        }

        return $this->render('historique/index.html.twig', [
            'groupedHistoriques' => $groupedHistoriques,
            'user' => $user
        ]);
    }

    
    #[Route('/add-to-history', name: 'add_to_history', methods: ['POST'])]
    public function addToHistory(Request $request, EntityManagerInterface $entityManager, HistoriqueRepository $historiqueRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['message' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        $musiqueId = $request->request->get('musiqueId');
        if (!$musiqueId) {
            return $this->json(['message' => 'Invalid request'], Response::HTTP_BAD_REQUEST);
        }

        $musique = $entityManager->getRepository(Musique::class)->find($musiqueId);
        if (!$musique) {
            throw $this->createNotFoundException('Musique not found');
        }

        // Remove existing history entries for this musique
        $existingHistoriques = $historiqueRepository->findBy(['utilisateur' => $user, 'musique' => $musique]);
        foreach ($existingHistoriques as $historique) {
            $entityManager->remove($historique);
        }

        // Add new history entry
        $newHistorique = new Historique();
        $newHistorique->setUtilisateur($user);
        $newHistorique->setMusique($musique);
        $newHistorique->setDateEcoute(new \DateTime());

        $entityManager->persist($newHistorique);
        $entityManager->flush();

        return $this->json(['message' => 'History updated'], Response::HTTP_OK);
    }
}

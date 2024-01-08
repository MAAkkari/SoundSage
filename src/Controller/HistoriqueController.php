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

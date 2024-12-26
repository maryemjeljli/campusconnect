<?php

namespace App\Controller;

use App\Entity\Stage;
use App\Form\postulerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeStageController extends AbstractController
{
    #[Route('/homestage', name: 'app_homestage', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $stageRepository = $entityManager->getRepository(Stage::class);

        // Récupération des paramètres de recherche
        $domaine = $request->query->get('domaine');
        $localisation = $request->query->get('localisation');

        // Création d'une requête conditionnelle pour la recherche
        $queryBuilder = $stageRepository->createQueryBuilder('s');

        if ($domaine) {
            $queryBuilder->andWhere('s.domaine LIKE :domaine')
                ->setParameter('domaine', '%' . $domaine . '%');
        }

        if ($localisation) {
            $queryBuilder->andWhere('s.localisation LIKE :localisation')
                ->setParameter('localisation', '%' . $localisation . '%');
        }

        $stages = $queryBuilder->getQuery()->getResult();

        $selectedStage = null;
        $form = null;

        // Vérifie si un stage est sélectionné pour postuler
        if ($request->query->has('stage_id')) {
            $selectedStageId = $request->query->get('stage_id');
            $selectedStage = $stageRepository->find($selectedStageId);

            if ($selectedStage) {
                // Crée le formulaire pour postuler
                $form = $this->createForm(postulerType::class);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    // Gérer les données du formulaire ici (par exemple : enregistrement ou envoi d'email)
                    $this->addFlash('success', 'Votre candidature a été envoyée avec succès.');

                    return $this->redirectToRoute('app_homestage');
                }
            }
        }

        // Passer les données au template Twig
        return $this->render('homestage/index.html.twig', [
            'stages' => $stages,
            'selectedStage' => $selectedStage, // Stage sélectionné
            'form' => $form ? $form->createView() : null, // Vue du formulaire s'il existe
        ]);
    }
}

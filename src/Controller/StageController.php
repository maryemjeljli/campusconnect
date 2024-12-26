<?php

namespace App\Controller;

use App\Entity\Stage;
use App\Entity\Typestage;
use App\Form\postulerType;
use App\Form\StageType;
use App\Form\TypestageType;
use App\Repository\StageRepository;
use App\Repository\TypestageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/stage')]
final class StageController extends AbstractController
{
    #[Route('/show', name: 'app_stage_show', methods: ['GET', 'POST'])]
    public function show(
        StageRepository $stageRepository,
        TypestageRepository $typestageRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): Response {
        $stages = $stageRepository->findAll();
        $typestages = $typestageRepository->findAll();

        // Gestion de formulaire pour ajouter un stage
        $stage = new Stage();
        $stageForm = $this->createForm(StageType::class, $stage);
        $stageForm->handleRequest($request);

        if ($stageForm->isSubmitted() && $stageForm->isValid()) {
            $errors = $validator->validate($stage);
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            } else {
                $entityManager->persist($stage);
                $entityManager->flush();

                $this->addFlash('success', 'Stage ajouté avec succès.');
                return $this->redirectToRoute('app_stage_show');
            }
        }

        // Gestion de formulaire pour ajouter un type de stage
        $typestage = new Typestage();
        $typestageForm = $this->createForm(TypestageType::class, $typestage);
        $typestageForm->handleRequest($request);

        if ($typestageForm->isSubmitted() && $typestageForm->isValid()) {
            $entityManager->persist($typestage);
            $entityManager->flush();

            $this->addFlash('success', 'Type de stage ajouté avec succès.');
            return $this->redirectToRoute('app_stage_show');
        }

        // Fonction pour calculer les statistiques des stages par entreprise
        $query = $entityManager->createQuery(
            'SELECT s.entreprise AS company, COUNT(s.id) AS stageCount
             FROM App\Entity\Stage s
             WHERE s.entreprise IS NOT NULL
             GROUP BY s.entreprise
             ORDER BY stageCount DESC'
        );

        $stageStats = $query->getResult();

        return $this->render('stage/show.html.twig', [
            'stages' => $stages,
            'typestages' => $typestages,
            'stage_form' => $stageForm->createView(),
            'typestage_form' => $typestageForm->createView(),
            'stageStats' => $stageStats, // Transmettre les statistiques
        ]);
    }

    #[Route('/{id}/postuler', name: 'app_stage_postuler', methods: ['GET', 'POST'])]
    public function postuler(
        Stage $stage,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(postulerType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Votre candidature a été envoyée avec succès.');
            return $this->redirectToRoute('app_stage_show', ['id' => $stage->getId()]);
        }

        return $this->render('postuler.html.twig', [
            'stage' => $stage,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stage $stage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StageType::class, $stage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Stage modifié avec succès.');
            return $this->redirectToRoute('app_stage_show');
        }

        return $this->render('stage/edit.html.twig', [
            'stage' => $stage,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_stage_delete', methods: ['POST'])]
    public function delete(Request $request, Stage $stage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $stage->getId(), $request->request->get('_token'))) {
            $entityManager->remove($stage);
            $entityManager->flush();

            $this->addFlash('success', 'Stage supprimé avec succès.');
        }

        return $this->redirectToRoute('app_stage_show');
    }
}

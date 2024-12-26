<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\FormulaireFormationType;
use App\Form\TypeFormationType;
use App\Entity\Formation;
use App\Entity\TypeFormation;

class FormController extends AbstractController
{
    #[Route('/form', name: 'app_form')]
    public function index(
        Request $request, 
        EntityManagerInterface $entityManager
    ): Response {
        // Gestion du formulaire pour la création d'une Formation
        $formation = new Formation();
        $formationForm = $this->createForm(FormulaireFormationType::class, $formation);
        $formationForm->handleRequest($request);

        if ($formationForm->isSubmitted() && $formationForm->isValid()) {
            $entityManager->persist($formation);
            $entityManager->flush();

            $this->addFlash('success', 'La formation a été ajoutée avec succès.');
            return $this->redirectToRoute('app_form');
        }

        // Gestion du formulaire pour la création d'un Type de Formation
        $typeFormation = new TypeFormation();
        $typeForm = $this->createForm(TypeFormationType::class, $typeFormation);
        $typeForm->handleRequest($request);

        if ($typeForm->isSubmitted() && $typeForm->isValid()) {
            $entityManager->persist($typeFormation);
            $entityManager->flush();

            $this->addFlash('success', 'Le type de formation a été ajouté avec succès.');
            return $this->redirectToRoute('app_form');
        }

        // Rendu des formulaires dans la vue
        return $this->render('form.html.twig', [
            'formationForm' => $formationForm->createView(),
            'typeForm' => $typeForm->createView(),
        ]);
    }
}

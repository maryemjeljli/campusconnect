<?php

namespace App\Controller;

use App\Entity\TypeFormation;
use App\Form\TypeFormationType;
use App\Repository\TypeFormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TypeFormationController extends AbstractController
{
    #[Route('/type/formation', name: 'app_type_formation')]
    public function index(): Response
    {
        return $this->render('type_formation/index.html.twig', [
            'controller_name' => 'TypeFormationController',
        ]);
    }

    //afficher
    #[Route('/typeformation/affiche', name: 'app_typeformation_affiche')]
    public function affiche(TypeFormationRepository $typeFormationRepository): Response
    {
        // Récupérer tous les types de formation
        $typeFormations = $typeFormationRepository->findAll();

        // Retourner la vue Twig
        return $this->render('type_formation/affiche.html.twig', [
            'typeFormations' => $typeFormations,
        ]);
    }

    //ajouter
    #[Route('/typeformation/ajouter', name: 'app_typeformation_ajouter')]
    public function ajouter(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Crée une nouvelle instance de TypeFormation
        $typeFormation = new TypeFormation();

        // Crée le formulaire à partir de TypeFormationType
        $form = $this->createForm(TypeFormationType::class, $typeFormation);

        // Gère la requête HTTP pour le formulaire
        $form->handleRequest($request);

        // Vérifie si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Persiste les données et les enregistre dans la base
            $entityManager->persist($typeFormation);
            $entityManager->flush();

            // Ajoute un message de succès
            $this->addFlash('success', 'Type de formation ajouté avec succès.');

            // Redirige vers la page affichant la liste des types de formations
            return $this->redirectToRoute('app_afficherformation');
        }

        // Rend le formulaire à la vue Twig
        return $this->render('type_formation/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //delete
    #[Route('/typeformation/supprimer/{id}', name: 'app_typeformation_supprimer')]
    public function supprimer(TypeFormation $typeFormation, EntityManagerInterface $entityManager): Response
    {
        $formations = $typeFormation->getFormations();

    
        $entityManager->remove($typeFormation);
        $entityManager->flush();
    
        $this->addFlash('success', 'Le type de formation a été supprimé avec succès.');
        return $this->redirectToRoute('app_afficherformation');
    }
    

    //modifier
    // modifier
#[Route('/typeformation/modifier/{id}', name: 'app_typeformation_modifier')]
public function modifier(
    Request $request,
    EntityManagerInterface $entityManager,
    TypeFormationRepository $typeFormationRepository,
    int $id
): Response {
    // Récupère le type de formation existant par son ID
    $typeFormation = $typeFormationRepository->find($id);

    // Vérifie si le type de formation existe
    if (!$typeFormation) {
        // Si le type de formation n'existe pas, redirige vers la liste
        $this->addFlash('error', 'Type de formation non trouvé.');
        return $this->redirectToRoute('app_afficherformation');
    }

    // Crée le formulaire avec les données du type de formation existant
    $form = $this->createForm(TypeFormationType::class, $typeFormation);

    // Gère la requête HTTP pour le formulaire
    $form->handleRequest($request);

    // Vérifie si le formulaire a été soumis et est valide
    if ($form->isSubmitted() && $form->isValid()) {
        // Sauvegarde les modifications dans la base de données
        $entityManager->flush();

        // Ajoute un message de succès
        $this->addFlash('success', 'Type de formation modifié avec succès.');

        // Redirige vers la page affichant la liste des types de formations
        return $this->redirectToRoute('app_afficherformation');
    }

    // Rend le formulaire à la vue Twig
    return $this->render('type_formation/modifier.html.twig', [
        'form' => $form->createView(),
    ]);
}

}

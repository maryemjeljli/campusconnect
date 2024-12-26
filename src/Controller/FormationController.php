<?php

namespace App\Controller;


use App\Form\UserType;
use App\Repository\UserRepository;
use App\Entity\Formation;
use App\Entity\Inscription;
use App\Repository\InscriptionRepository;
use App\Form\FormulaireFormationType;
use App\Repository\FormationRepository;
use App\Repository\TypeFormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use App\Entity\Evaluation;


class FormationController extends AbstractController
{
    // Afficher la liste des formations
    #[Route('/afficherformation', name: 'app_afficherformation')]
    public function afficher(FormationRepository $formationRep, TypeFormationRepository $typeFormationRepository): Response
    {
        // Récupérer toutes les formations
        $formations = $formationRep->findAll();
        $typeFormations = $typeFormationRepository->findAll();

        // Rendre la vue avec les formations
        return $this->render('formation/afficherformation.html.twig', [
            'formations' => $formations,
            'typeFormations' => $typeFormations,
        ]);
    }

    //ajouter une formation
    #[Route('/new', name: 'formation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FormationRepository $formationRepository, EntityManagerInterface $entityManager): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormulaireFormationType::class, $formation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
    
            // Appel à la méthode du repository pour gérer l'image
            $formationRepository->handleImage($formation, $imageFile);
    
            $entityManager->persist($formation);
            $entityManager->flush();
    
            $this->addFlash('success', 'La formation a été enregistrée avec succès !');
            return $this->redirectToRoute('app_afficherformation');
        }
    
        return $this->render('formation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    // Modifier une formation existante
#[Route('/editer/{nom}', name: 'app_editerformation', methods: ['GET', 'POST'])]
public function edit(string $nom, Request $request, FormationRepository $formationRepository, EntityManagerInterface $entityManager): Response
{
    $formation = $formationRepository->findOneBy(['nom' => $nom]);

    if (!$formation) {
        throw $this->createNotFoundException('Formation non trouvée pour le nom : ' . $nom);
    }

    $form = $this->createForm(FormulaireFormationType::class, $formation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('image')->getData();

        // Appel au repository pour gérer l'image
        $formationRepository->handleImage($formation, $imageFile);

        $entityManager->flush();
        $this->addFlash('success', 'Formation mise à jour avec succès !');
        return $this->redirectToRoute('app_afficherformation');
    }

    return $this->render('formation/editer.html.twig', [
        'form' => $form->createView(),
        'formation' => $formation,
    ]);
}


    // Supprimer une formation
    #[Route('/delete/{nom}', name: 'formation_delete', methods: ['POST', 'GET'])]
    public function delete(Formation $formation, FormationRepository $formationRepository, EntityManagerInterface $entityManager): Response
    {
        // Appel au repository pour supprimer l'image
        $formationRepository->deleteImage($formation);
    
        $entityManager->remove($formation);
        $entityManager->flush();
    
        $this->addFlash('success', 'La formation a été supprimée avec succès.');
        return $this->redirectToRoute('app_afficherformation');
    }
    


    

    #[Route('/rechercher-inscription', name: 'app_rechercher_inscription', methods: ['GET', 'POST'])]
    public function rechercherUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        $searchTerm = $request->query->get('search', ''); // Terme de recherche général
        $userNom = $request->query->get('userNom', '');   // Recherche par nom précis
    
        $inscriptions = [];
    
        // Priorité à la recherche par 'userNom' si disponible
        if (!empty($userNom)) {
            $inscriptions = $entityManager->getRepository(Inscription::class)->findBy(['userNom' => $userNom]);
        } elseif (!empty($searchTerm)) {
            // Sinon, effectuer la recherche générale sur 'searchTerm'
            $inscriptions = $entityManager->getRepository(Inscription::class)->createQueryBuilder('i')
                ->where('i.userNom LIKE :search OR i.userAdresse LIKE :search')
                ->setParameter('search', '%' . $searchTerm . '%')
                ->getQuery()
                ->getResult();
        }
    
        // Rendre la vue avec les résultats
        return $this->render('formation/user_profile_recherche.html.twig', [
            'inscriptions' => $inscriptions, // Liste des utilisateurs trouvés
            'searchTerm' => $searchTerm,     // Pré-remplissage du champ de recherche
            'userNom' => $userNom,           // Nom spécifique si filtré par 'userNom'
        ]);
    }




    
    
    
    

}

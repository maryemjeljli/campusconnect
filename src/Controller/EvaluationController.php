<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Form\UserType;
use App\Form\EvaluationType;
use App\Repository\UserRepository;
use App\Entity\Formation;
use App\Entity\Inscription;
use App\Repository\InscriptionRepository;
use App\Form\FormulaireFormationType;
use App\Repository\FormationRepository;
use App\Repository\TypeFormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use App\Entity\Evaluation;

class EvaluationController extends AbstractController
{
    #[Route('/evaluation', name: 'app_evaluation_index')]
    public function index(): Response
    {
        return $this->render('evaluation/index.html.twig', [
            'controller_name' => 'EvaluationController',
        ]);
    }

    #[Route('/evaluation/saisir/{id}', name: 'app_evaluation', methods: ['GET', 'POST'])]
    public function saisirEvaluation(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Rechercher l'inscription par ID
        $inscription = $entityManager->getRepository(Inscription::class)->find($id);
        if (!$inscription) {
            $this->addFlash('error', 'Inscription introuvable.');
            return $this->redirectToRoute('app_evaluation'); // Redirige vers la liste des évaluations ou une page d'erreur
        }
    
        // Rechercher ou créer une évaluation pour cette inscription
        $evaluation = $entityManager->getRepository(Evaluation::class)->findOneBy(['inscription' => $inscription]);
        if (!$evaluation) {
            $evaluation = new Evaluation();
            $evaluation->setInscription($inscription);
        }
    
        // Créer le formulaire
        $form = $this->createForm(EvaluationType::class, $evaluation);
    
        $form->handleRequest($request);
    
        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde en base de données
            $entityManager->persist($evaluation);
            $entityManager->flush();
    
            $this->addFlash('success', 'Évaluation enregistrée avec succès.');
            return $this->redirectToRoute('app_evaluation', ['id' => $id]);
        }
    
        // Passer le formulaire et l'inscription à la vue
        return $this->render('evaluation/evaluer.html.twig', [
            'form' => $form->createView(),
            'inscription' => $inscription,
        ]);
    }
    
    
    

      
    #[Route('/evaluation/liste/{id}', name: 'app_liste_evaluations', methods: ['GET'])]
    public function listeEvaluations(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // Récupérer l'inscription par ID
        $inscription = $entityManager->getRepository(Inscription::class)->find($id);
    
        if (!$inscription) {
            $this->addFlash('error', 'Inscription introuvable.');
            return $this->redirectToRoute('app_liste_evaluations', ['id' => $id]); // Rediriger vers l'évaluation
        }
    
        // Utilisation de DQL pour récupérer toutes les évaluations associées à l'inscription
        $query = $entityManager->getRepository(Evaluation::class)
            ->createQueryBuilder('e')
            ->innerJoin('e.inscription', 'i')  // Jointure avec l'entité Inscription
            ->where('e.inscription = :inscription')  // Condition de la requête pour filtrer par inscription
            ->setParameter('inscription', $inscription)  // Passer l'ID de l'inscription comme paramètre
            ->getQuery();
    
        // Exécuter la requête et récupérer les résultats
        $evaluations = $query->getResult();
    
        return $this->render('evaluation/liste_evaluation.html.twig', [
            'inscription' => $inscription, // Passer l'objet inscription à la vue
            'userNom' => $inscription->getUserNom(),
            'evaluations' => $evaluations,
        ]);
    }
    
    

    #[Route('/evaluation/supprimer/{userNom}/{id}', name: 'app_evaluation_delete', methods: ['POST'])]
    public function deleteEvaluation(string $userNom, int $id, EntityManagerInterface $entityManager): Response
    {
        // Trouver l'inscription de l'utilisateur par son nom
        $inscription = $entityManager->getRepository(Inscription::class)->findOneBy(['userNom' => $userNom]);
    
        if (!$inscription) {
            $this->addFlash('error', 'Inscription introuvable pour cet utilisateur.');
            return $this->redirectToRoute('app_evaluation'); // Retour à la recherche
        }
    
        // Trouver l'évaluation spécifique pour cette inscription
        $evaluation = $entityManager->getRepository(Evaluation::class)->findOneBy([
            'id' => $id,
            'inscription' => $inscription,
        ]);
    
        if (!$evaluation) {
            $this->addFlash('error', 'Évaluation introuvable pour cet utilisateur.');
            return $this->redirectToRoute('app_liste_evaluations', ['userNom' => $userNom]); // Rester sur la liste
        }
    
        // Supprimer l'évaluation
        $entityManager->remove($evaluation);
        $entityManager->flush();
    
        $this->addFlash('success', 'L\'évaluation a été supprimée avec succès.');
    
        // Redirection vers la liste des évaluations avec le bon userNom
        return $this->redirectToRoute('app_liste_evaluations', ['id' => $inscription->getId()]);
    }
    
}

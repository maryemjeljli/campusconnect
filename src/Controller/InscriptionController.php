<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Form\UserType;
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
use Symfony\Component\HttpFoundation\RedirectResponse;  // Ajoute cette ligne

use App\Entity\User;
use App\Entity\Evaluation;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function index(): Response
    {
        return $this->render('inscription/index.html.twig', [
            'controller_name' => 'InscriptionController',
        ]);
    }

    #[Route('/inscription/supprimer/{id}', name: 'app_inscription_delete', methods: ['POST'])]
    public function supprimerInscription(
        int $id, 
        Request $request, 
        EntityManagerInterface $entityManager
    ): RedirectResponse {
        // Récupérer l'inscription à supprimer
        $inscription = $entityManager->getRepository(Inscription::class)->find($id);
    
        if (!$inscription) {
            $this->addFlash('error', 'Inscription non trouvée');
            return $this->redirectToRoute('app_rechercher_inscription');
        }
    
        // Récupérer le nom de la formation à partir de l'inscription
        $formationNom = $inscription->getFormationNom();
    
        if ($formationNom) {
            // Trouver la formation par son nom
            $formation = $entityManager->getRepository(Formation::class)->findOneBy(['nom' => $formationNom]);
    
            if ($formation) {
                // Incrémenter le nombre de places disponibles
                $formation->setPlaces($formation->getPlaces() + 1);
                $entityManager->persist($formation);
            }
        }
    
        // Supprimer l'inscription
        $entityManager->remove($inscription);
        $entityManager->flush();
    
        // Ajouter un message flash
        $this->addFlash('success', 'Inscription supprimée avec succès et place disponible rétablie.');
    
        // Récupérer les paramètres pour la redirection
        $searchTerm = $request->request->get('search', '');
        $userNom = $request->request->get('userNom', '');
    
        // Rediriger vers la page avec les mêmes filtres
        return $this->redirectToRoute('app_rechercher_inscription', [
            'search' => $searchTerm,
            'userNom' => $userNom,
        ]);
    }
    

    #[Route('/statistiques', name: 'app_statistiques')]
    public function statistiques(EntityManagerInterface $entityManager): Response
    {
        // Récupérer le repository d'inscription
        $inscriptionRepository = $entityManager->getRepository(Inscription::class);
    
        // Obtenir les statistiques par formation
        $stats = $inscriptionRepository->countInscriptionsByFormation();
    
        // Préparer les données pour le graphique
        $labels = [];
        $data = [];
        foreach ($stats as $stat) {
            $labels[] = $stat['formation'];
            $data[] = $stat['total'];
        }
    
        // Renvoyer à la vue
        return $this->render('formation/statistique.html.twig', [
            'labels' => json_encode($labels),
            'data' => json_encode($data),
        ]);
    }
    
    
    
    
    
    
    
}

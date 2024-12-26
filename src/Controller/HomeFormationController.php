<?php


namespace App\Controller;
use App\Entity\Inscription;
use App\Entity\Formation;
use App\Entity\User;
use App\Repository\FormationRepository;
use App\Repository\InscriptionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class HomeFormationController extends AbstractController
{
    #[Route('/home/formation', name: 'app_home_formation')]
    public function index(FormationRepository $formationRepository): Response
    {
        // Récupérer toutes les formations
        $formations = $formationRepository->findAll();

        return $this->render('home_formation/index.html.twig', [
            'formations' => $formations, // Passer les formations à la vue
        ]);
    }
    
    #[Route('/formation/participer/{id}', name: 'app_participer_formation', methods: ['GET'])]
    public function participer(
        int $id,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response {
        // Récupère la formation par son ID
        $formation = $entityManager->getRepository(Formation::class)->find($id);
    
        // Si la formation n'existe pas, afficher un message d'erreur
        if (!$formation) {
            $this->addFlash('danger', 'Formation introuvable.');
            return $this->redirectToRoute('app_home_formation');
        }
    
        // Récupère l'utilisateur connecté
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('warning', 'Vous devez être connecté pour participer.');
            return $this->redirectToRoute('app_login');
        }
    
        // Vérifie si l'utilisateur est déjà inscrit à cette formation
        $existingInscription = $entityManager->getRepository(Inscription::class)
            ->findOneBy([
                'userId' => $user->getId(),
                'formationNom' => $formation->getNom(),
            ]);
    
        if ($existingInscription) {
            $this->addFlash('warning', 'Vous êtes déjà inscrit à cette formation.');
            return $this->redirectToRoute('app_home_formation');
        }
    
        // Vérifie si la formation a des places disponibles
        if ($formation->getPlaces() <= 0) {
            $this->addFlash('warning', 'Il n\'y a plus de places disponibles pour cette formation.');
            return $this->redirectToRoute('app_home_formation');
        }
    
        // Crée une nouvelle inscription
        $inscription = new Inscription();
        $inscription->setUserId($user->getId());
        $inscription->setUserNom($user->getNom());
        $inscription->setUserAdresse($user->getEmail());
        $inscription->setUserNumero($user->getPhone());
        $inscription->setFormationNom($formation->getNom());
        $inscription->setDateInscription(new \DateTime());
    
        // Enregistre l'inscription dans la base de données
        $entityManager->persist($inscription);
        $entityManager->flush();
    
        // Décrémente le nombre de places disponibles
        $formation->setPlaces($formation->getPlaces() - 1);
        $entityManager->flush(); // Sauvegarde le changement du nombre de places
    
    
        // Affiche un message de succès à l'utilisateur
        $this->addFlash('success', 'Vous êtes inscrit à la formation "' . $formation->getNom() . '".');
    
        // Redirige l'utilisateur vers la page des formations
        return $this->redirectToRoute('app_home_formation');
    }

    
    

}


<?php

namespace App\Controller;

use App\Entity\Club; 
use App\Form\FormulaireClubType; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ClubRepository;
use App\Repository\MembershipRepository;
use Symfony\Component\Form\FormTypeInterface;
use App\Entity\Membership;
use App\Form\MembershipType;




class ClubController extends AbstractController
{
    #[Route('/clubs', name: 'club_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupération de tous les clubs
        $clubs = $entityManager->getRepository(Club::class)->findAll();

        return $this->render('club/index.html.twig', [
            'clubs' => $clubs,
        ]);
    }

    #[Route('/clubs/add', name: 'club_add')]
    public function addClub(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Création d'un nouvel objet Club
        $club = new Club();

        // Création du formulaire
        $form = $this->createForm(FormulaireClubType::class, $club);

        // Gestion de la soumission du formulaire
        $form->handleRequest($request);

        // Vérification si le formulaire a été soumis et validé
        if ($form->isSubmitted()&& $form->isValid()) {
            // Persister l'objet Club dans la base de données
           
            $entityManager->persist($club);
            $entityManager->flush();

            // Redirection vers la page de la liste des clubs
            return $this->redirectToRoute('club_add');
        }
  
            $clubs = $entityManager->getRepository(Club::class)->findAll();
           
            // Pass the form and the list of clubs to the template
            return $this->render('club/add.html.twig', [
                'form' => $form->createView(),
                
                'clubs' => $clubs,
                 
                
            ]);
            

    }


    #[Route('/clubs/supprimer/{id}', name: 'club_delete')]
    public function deletC($id,ManagerRegistry $doc,ClubRepository $rep): Response
    {   //find club
        $club=$rep->find($id);
        //delete club
        $em=$doc->getManager();
        $em->remove($club);
        $em->flush();//commit au niv  de base de données

        return $this->redirectToRoute('club_add');
    }
    #[Route('/clubs/edit/{id}', name: 'club_modify')]
    public function editClub($id,Request $request, EntityManagerInterface $entityManager,ClubRepository $rep): Response
    {
        //find club
        $club=$rep->find($id);

        
        $form = $this->createForm(FormulaireClubType::class, $club);

        
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->flush();

            
            return $this->redirectToRoute('club_add');
        }

        // Affichage du formulaire d'ajout
        return $this->render('club/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
      // Route pour rejoindre un club
    #[Route('/club/join/{id}', name: 'club_join', methods: ['POST'])]
    public function joinClub(
        Club $club,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('warning', 'Vous devez être connecté pour rejoindre un club.');
            return $this->redirectToRoute('app_login');
        }

        // Vérifie si l'utilisateur est déjà membre du club
        $existingMembership = $entityManager->getRepository(Membership::class)
            ->findOneBy([
                'student' => $user,
                'club' => $club,
            ]);

        if ($existingMembership) {
            
            return $this->redirectToRoute('club_index');
        }

        // Crée une nouvelle adhésion
        $membership = new Membership();
        $membership->setStudent($user);
        $membership->setClub($club);
        $membership->setStudentEmail($user->getEmail());
        $membership->setPhoneNumber($user->getPhone());
        $membership->setJoinedAt(new \DateTime());

        // Enregistre l'adhésion dans la base de données
        $entityManager->persist($membership);
        $entityManager->flush();

        $this->addFlash('success', 'Votre demande pour rejoindre le club "' . $club->getNom() . '" a été envoyée avec succès.');

        return $this->redirectToRoute('club_index');
    }

    #[Route('/club/{id}/memberships', name: 'club_membership', methods: ['GET'])]
    public function showMembershipRequests(Club $club, EntityManagerInterface $entityManager): Response
    {
        // Récupère toutes les demandes d'adhésion pour ce club
        $memberships = $entityManager->getRepository(Membership::class)->findBy(['club' => $club]);
        
        return $this->render('club/membership.html.twig', [
            'club' => $club,
            'memberships' => $memberships,
        ]);
    }
    #[Route('/club/accept/{id}', name: 'club_accept_membership', methods: ['POST'])]
    public function acceptMembership(Membership $membership, EntityManagerInterface $entityManager): Response
    {
        // Mettre à jour l'adhésion pour accepter
        $membership->setStatus('accepted');
        $entityManager->flush();
        
        $this->addFlash('success', 'L\'adhésion a été acceptée.');
        return $this->redirectToRoute('club_membership', ['id' => $membership->getClub()->getId()]);
    }
    
    #[Route('/club/reject/{id}', name: 'club_reject_membership', methods: ['POST'])]
    public function rejectMembership(Membership $membership, EntityManagerInterface $entityManager): Response
    {
        // Mettre à jour l'adhésion pour refuser
        $membership->setStatus('rejected');
        $entityManager->flush();
        
        $this->addFlash('warning', 'L\'adhésion a été rejetée.');
        return $this->redirectToRoute('club_membership', ['id' => $membership->getClub()->getId()]);
    }
    
}
    
 
      



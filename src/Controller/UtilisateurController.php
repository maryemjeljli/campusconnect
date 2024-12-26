<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService; // N'oublie pas d'ajouter cette ligne pour le service
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\UserType;
use App\Form\adminType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class UtilisateurController extends AbstractController
{
    // Page d'accueil ou liste des utilisateurs
    #[Route('/utilisateur', name: 'app_utilisateur')]
    public function index(ManagerRegistry $doctrine): Response
    {
        // Récupérer tous les utilisateurs de la base de données
        $users = $doctrine->getRepository(User::class)->findAll();

        return $this->render('utilisateur/index.html.twig', [
            'users' => $users,
        ]);
    }

    // Ajouter un utilisateur
    #[Route('/user/adduser', name: 'app_adduser')]
    public function ajouter(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(adminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hacher le mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_utilisateur'); // Redirige vers la liste des utilisateurs
        }

        return $this->render('utilisateur/adduser.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Modifier un utilisateur
    #[Route('/user/{id}/updateuser', name: 'app_updateuser')]
    public function updateUser(Request $request, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine, int $id): Response
{
    $entityManager = $doctrine->getManager();
    $user = $entityManager->getRepository(User::class)->find($id);

    if (!$user) {
        throw $this->createNotFoundException('Utilisateur non trouvé.');
    }

    // Créer et traiter le formulaire
    $form = $this->createForm(adminType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Vérifiez si un nouveau mot de passe a été fourni
        if ($form->get('password')->getData()) {
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
        }

        $entityManager->flush(); // Mise à jour des données
        return $this->redirectToRoute('app_utilisateur'); // Redirection après succès
    }

    return $this->render('utilisateur/updateuser.html.twig', [
        'form' => $form->createView(),
    ]);
}
    #[Route('/user/list', name: 'app_afficheuser')]
    public function list(ManagerRegistry $doctrine): Response
    {
        $users = $doctrine->getRepository(User::class)->findAll();
    
        return $this->render('utilisateur/list.html.twig', [
            'users' => $users,
        ]);
    
    }


    // Supprimer un utilisateur
    #[Route('/user/{id}/deleteuser', name: 'admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_utilisateur'); // Redirige vers la liste des utilisateurs
    }
    
       #[Route('/rechercher-user', name: 'app_rechercher_user', methods: ['GET'])]
public function rechercherUser(Request $request, UserRepository $userRepository): Response
{
    $searchTerm = $request->query->get('search', '');

    // Rechercher les utilisateurs correspondant au terme
    $users = $userRepository->createQueryBuilder('u')
        ->where('u.nom LIKE :search OR u.email LIKE :search')
        ->setParameter('search', '%' . $searchTerm . '%')
        ->getQuery()
        ->getResult();

    // Rendre la vue avec les résultats
    return $this->render('utilisateur/chercher.html.twig', [
        'users' => $users,
        'searchTerm' => $searchTerm,
    ]);
}
// Injection du service dans le constructeur
public function __construct(UserService $UserService)
{
    $this->UserService = $UserService;
}// src/Controller/UtilisateurController.php

// Approuver un utilisateur
#[Route('/users/{id}/approve', name: 'approve_user', methods: ['PUT'])]
public function approveUser(User $user): JsonResponse
{
    $this->UserService->approveUser($user); // Approuver l'utilisateur via le service

    return new JsonResponse([
        'status' => 'success',
        'message' => 'Utilisateur approuvé avec succès',
    ]);
}

// Bannir un utilisateur
#[Route('/users/{id}/ban', name: 'ban_user', methods: ['PUT'])]
public function banUser(User $user): JsonResponse
{
    $this->UserService->banUser($user); // Bannir l'utilisateur via le service

    return new JsonResponse([
        'status' => 'success',
        'message' => 'Utilisateur banni avec succès',
    ]);
}

#[Route('/filtrer-user', name: 'app_user_filter', methods: ['GET'])]


public function filter(Request $request, UserRepository $userRepository)
{
   // Récupérer le rôle à partir du paramètre GET
   $role = $request->query->get('role', null);

   // Si un rôle est spécifié, filtrer les utilisateurs par ce rôle
   if ($role) {
       $users = $userRepository->findByRole($role);
   } else {
       // Sinon, afficher tous les utilisateurs
       $users = $userRepository->findAll();
   }

   return $this->render('utilisateur/filtrer.html.twig', [
       'users' => $users,
   ]);
}
#[Route('/statistics', name: 'user_statistics')]
public function stat(ManagerRegistry $doctrine): Response
{
    
        // Récupérer le nombre d'hommes et de femmes dans la base de données
        $entityManager = $doctrine->getManager();

        // Compter le nombre d'hommes (sexe = 'M')
        $maleCount = $entityManager->createQuery('
            SELECT COUNT(u.id)
            FROM App\Entity\User u
            WHERE u.sexe = :sexe
        ')
        ->setParameter('sexe', 'M')
        ->getSingleScalarResult();

        // Compter le nombre de femmes (sexe = 'F')
        $femaleCount = $entityManager->createQuery('
            SELECT COUNT(u.id)
            FROM App\Entity\User u
            WHERE u.sexe = :sexe
        ')
        ->setParameter('sexe', 'F')
        ->getSingleScalarResult();

        // Passer ces données à la vue pour les afficher
        return $this->render('utilisateur/stat.html.twig', [
            'maleCount' => $maleCount,
            'femaleCount' => $femaleCount,
        ]);
    }
}
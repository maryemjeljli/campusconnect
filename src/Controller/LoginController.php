<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Vérifie si l'utilisateur est déjà connecté
        if ($this->getUser()) {
            // Redirige l'administrateur vers le tableau de bord admin
            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_utilisateur');
            }
            // Redirige les autres utilisateurs vers la page d'accueil
            return $this->redirectToRoute('app_home'); // Remplacez par votre route d'accueil
        }

        // Récupère les erreurs d'authentification (s'il y en a)
        $error = $authenticationUtils->getLastAuthenticationError();

        // Récupère le dernier email utilisé pour tenter de se connecter
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/login.html.twig', [
            'last_username' => $lastUsername, // Pour pré-remplir le champ email
            'error' => $error, // Affiche un message d'erreur (s'il y en a)
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Symfony gère automatiquement la déconnexion
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
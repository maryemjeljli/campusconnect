<?php
// src/Controller/PasswordResetController.php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\String\Slugger\SluggerInterface;

class PasswordResetController extends AbstractController
{
    #[Route('/password/forgot', name: 'app_password_forgot')]
    public function forgot(Request $request, ManagerRegistry $doctrine, MailerInterface $mailer, SluggerInterface $slugger): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $doctrine->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user) {
                // Générer un token aléatoire
                $token = $slugger->slug(uniqid());
                $user->setResetToken($token);
                $user->setResetTokenExpiresAt((new \DateTime())->modify('+1 hour'));

                // Enregistrer dans la base de données
                $entityManager = $doctrine->getManager();
                $entityManager->flush();

                // Envoyer l'email
                $resetLink = $this->generateUrl('app_password_reset', ['token' => $token], true);
                $email = (new Email())
                    ->from('mayssakammoun70@gmail.com')
                    ->to($user->getEmail())
                    ->subject('Réinitialisation de mot de passe')
                    ->html("<p>Cliquez sur le lien pour réinitialiser votre mot de passe : <a href='{$resetLink}'>Réinitialiser</a></p>");
                $mailer->send($email);

                $this->addFlash('success', 'Un email a été envoyé pour réinitialiser votre mot de passe.');
            } else {
                $this->addFlash('error', 'Aucun utilisateur trouvé avec cet email.');
            }
        }

        return $this->render('password/forget.html.twig');
    }

    #[Route('/password/reset/{token}', name: 'app_password_reset')]
public function reset(string $token, Request $request, ManagerRegistry $doctrine): Response
{
    // Récupérer l'utilisateur via le token
    $user = $doctrine->getRepository(User::class)->findOneBy(['resetToken' => $token]);

    // Vérifier si l'utilisateur existe et si le token n'est pas expiré
    if (!$user || $user->getResetTokenExpiresAt() < new \DateTime()) {
        $this->addFlash('error', 'Le lien de réinitialisation est invalide ou expiré.');
        return $this->redirectToRoute('app_password_forgot');
    }

    // Si le formulaire est soumis via POST
    if ($request->isMethod('POST')) {
        $newPassword = $request->request->get('password');
        
        // Hashage du mot de passe
        $user->setPassword(password_hash($newPassword, PASSWORD_BCRYPT)); // Utilisez un encodeur sécurisé
        $user->setResetToken(null); // Réinitialiser le token
        $user->setResetTokenExpiresAt(null); // Réinitialiser la date d'expiration du token

        // Sauvegarder l'utilisateur dans la base de données
        $entityManager = $doctrine->getManager();
        $entityManager->flush();

        $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès.');

        return $this->redirectToRoute('app_login');
    }

    // Générer l'URL de réinitialisation avec le token
    $resetUrl = $this->generateUrl('app_password_reset', ['token' => $token], true);

    // Passer l'URL au template
    return $this->render('password/reset.html.twig', [
        'token' => $token,
        'url' => $resetUrl,  // Passer la variable 'url' au template
    ]);
}

#[Route('/send-email', name: 'send_custom_email')]
    public function sendCustomEmail(Request $request, MailerInterface $mailer): Response
    {
        // Vérifier si le formulaire a été soumis
        if ($request->isMethod('POST')) {
            $emailRecipient = $request->request->get('email'); // Email du destinataire

            // Créer un objet Email
            $email = (new Email())
                ->from('mayssakammoun70@gmail.com')  // Votre email
                ->to($emailRecipient)                // Email destinataire
                ->subject('Message de Symfony')      // Sujet
                ->text('Ceci est un message envoyé depuis Symfony.'); // Corps du message

            try {
                // Envoyer l'email
                $mailer->send($email);
                $this->addFlash('success', 'E-mail envoyé avec succès.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
            }

            // Rediriger ou rendre une réponse
            return $this->redirectToRoute('send_custom_email');
        }

        // Afficher le formulaire
        return $this->render('password/send_email.html.twig');
    }
}
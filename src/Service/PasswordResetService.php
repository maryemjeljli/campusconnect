<?php
// src/Service/PasswordResetService.php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class PasswordResetService
{
    private $mailer;
    private $em;

    public function __construct(MailerInterface $mailer, EntityManagerInterface $em)
    {
        $this->mailer = $mailer;
        $this->em = $em;
    }

    public function sendResetLink(string $email): bool
    {
        $user = $this->em->getRepository(User::class)->findOneByEmail($email);

        if (!$user) {
            return false; // L'utilisateur n'existe pas
        }

        // Générer un token unique
        $token = bin2hex(random_bytes(16));  // Génère un token aléatoire de 32 caractères
        $user->setResetToken($token);
        $this->em->flush();

        // Envoi de l'email
        $emailMessage = (new Email())
            ->from('no-reply@votre-app.com')
            ->to($email)
            ->subject('Réinitialisation de votre mot de passe')
            ->html('<p>Cliquez sur le lien suivant pour réinitialiser votre mot de passe : <a href="https://votre-app.com/reinitialiser-mot-de-passe/' . $token . '">Réinitialiser le mot de passe</a></p>');

        $this->mailer->send($emailMessage);

        return true;
    }
}
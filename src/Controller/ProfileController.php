<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Form\ProfileType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile/{id}/edit', name: 'profile_edit')]
    public function edit(
        Request $request,
        UserPasswordHasherInterface $passwordHasher, // Correct type-hinting
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté
        $originalPassword = $user->getPassword(); // Sauvegarder le mot de passe actuel

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('password')->getData();

            if ($newPassword) {
                // Encoder et mettre à jour le mot de passe
                $encodedPassword = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($encodedPassword);
            } else {
                // Conserver l'ancien mot de passe
                $user->setPassword($originalPassword);
            }

            // Sauvegarder les modifications
            $entityManager->flush();

            return $this->redirectToRoute('profile_view'); // Redirection après succès
        }

        return $this->render('profile/edit_profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    
    #[Route('/profile', name: 'profile_view')]
    public function viewProfile(): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Formatage de la date de naissance
        $birthdate = $user->getBirthdate();

        // Passer les données au template
        return $this->render('profile/view_profile.html.twig', [
            'user' => $user,
            'birthdate' => $birthdate ? $birthdate->format('Y-m-d') : null, // Formater la date
        ]);
    }
}
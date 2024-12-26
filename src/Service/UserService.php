<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private $entityManager;

    // Injection de dépendance de l'EntityManager
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Méthode pour approuver un utilisateur
    public function approveUser(User $user): User
    {
        $user->setApproved(true);   // Marque l'utilisateur comme approuvé
        $user->setBanned(false);    // Assure qu'il n'est pas banni

        // Sauvegarde des modifications dans la base de données
        $this->entityManager->flush();

        return $user;
    }

    // Méthode pour bannir un utilisateur
    public function banUser(User $user): User
    {
        $user->setBanned(true);     // Marque l'utilisateur comme banni
        $user->setApproved(false);  // Assure qu'il n'est pas approuvé

        // Sauvegarde des modifications dans la base de données
        $this->entityManager->flush();

        return $user;
    }
}
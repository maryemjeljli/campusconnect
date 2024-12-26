<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer', unique: true)]
    #[Assert\NotBlank(message: 'Le Identifiant est obligatoire.')]
    #[Assert\Length(
        min: 8,
        max: 8,
        exactMessage: 'L\'identifiant doit comporter exactement 8 caractères.'
    )]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire.')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Le nom doit contenir au moins 2 caractères.',
        maxMessage: 'Le nom ne peut pas dépasser 50 caractères.'
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: 'L\'email est obligatoire.')]
    #[Assert\Email(message: 'Veuillez entrer une adresse email valide.')]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ['registration'], message: 'Le mot de passe est obligatoire.')]
    #[Assert\Length(
        min: 8,
        max: 20,
        minMessage: 'Le mot de passe doit contenir au moins 8 caractères.',
        maxMessage: 'Le mot de passe ne peut pas dépasser 20 caractères.',
        groups: ['registration']
    )]
    #[Assert\Regex(
        pattern: '/^(?=.*[a-zA-Z])(?=.*\d).+$/',
        message: 'Le mot de passe doit contenir au moins une lettre et un chiffre.',
        groups: ['registration']
    )]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $isBanned = false;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $isApproved = false;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sexe = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birthdate = null;
    
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $resetToken = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $resetTokenExpiresAt = null;

    // Getters et setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function isBanned(): ?bool
    {
        return $this->isBanned;
    }

    public function setBanned(?bool $isBanned): static
    {
        $this->isBanned = $isBanned;
        return $this;
    }

    public function isApproved(): ?bool
    {
        return $this->isApproved;
    }

    public function setApproved(?bool $isApproved): static
    {
        $this->isApproved = $isApproved;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles ?? [];
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // Si des données sensibles doivent être effacées après usage, ajoutez-les ici.
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): static
    {
        $this->sexe = $sexe;
        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;
        return $this;
    }

    public function getResetTokenExpiresAt(): ?\DateTimeInterface
    {
        return $this->resetTokenExpiresAt;
    }

    public function setResetTokenExpiresAt(?\DateTimeInterface $expiresAt): self
    {
        $this->resetTokenExpiresAt = $expiresAt;
        return $this;
    }
}
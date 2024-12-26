<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\InscriptionRepository;

#[ORM\Entity(repositoryClass: InscriptionRepository::class)]
class Inscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    // Informations sur l'utilisateur
    #[ORM\Column(type: 'integer', nullable: true)]
    private $userId;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $userNom;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $userAdresse;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private $userNumero;

    // Informations sur la formation
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $formationNom;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $dateInscription;

    // Relation OneToMany vers Evaluation avec cascade de suppression
    #[ORM\OneToMany(mappedBy: 'inscription', targetEntity: Evaluation::class, cascade: ['remove'])]
    private $evaluations;

    public function __construct()
    {
        // Initialiser le tableau d'évaluations (vide au départ)
        $this->evaluations = new ArrayCollection();
    }

    // Getters et setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getUserNom(): ?string
    {
        return $this->userNom;
    }

    public function setUserNom(?string $userNom): self
    {
        $this->userNom = $userNom;
        return $this;
    }

    public function getUserAdresse(): ?string
    {
        return $this->userAdresse;
    }

    public function setUserAdresse(?string $userAdresse): self
    {
        $this->userAdresse = $userAdresse;
        return $this;
    }

    public function getUserNumero(): ?string
    {
        return $this->userNumero;
    }

    public function setUserNumero(?string $userNumero): self
    {
        $this->userNumero = $userNumero;
        return $this;
    }

    public function getFormationNom(): ?string
    {
        return $this->formationNom;
    }

    public function setFormationNom(?string $formationNom): self
    {
        $this->formationNom = $formationNom;
        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(?\DateTimeInterface $dateInscription): self
    {
        $this->dateInscription = $dateInscription;
        return $this;
    }

    // Getter pour obtenir les évaluations liées à cette inscription
    public function getEvaluations(): Collection
    {
        return $this->evaluations;
    }

    // Setter pour ajouter une évaluation à l'inscription
    public function addEvaluation(Evaluation $evaluation): self
    {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations[] = $evaluation;
            $evaluation->setInscription($this);
        }
        return $this;
    }

    // Méthode pour supprimer une évaluation
    public function removeEvaluation(Evaluation $evaluation): self
    {
        if ($this->evaluations->contains($evaluation)) {
            $this->evaluations->removeElement($evaluation);
            // Si nécessaire, réinitialiser l'objet d'évaluation
            if ($evaluation->getInscription() === $this) {
                $evaluation->setInscription(null);
            }
        }
        return $this;
    }
}

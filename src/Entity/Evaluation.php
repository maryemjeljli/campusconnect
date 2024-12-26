<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class Evaluation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    // Informations sur l'utilisateur inscrit (lié à Inscription)
    #[ORM\ManyToOne(targetEntity: Inscription::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $inscription;

    // Critères d'évaluation
    #[ORM\Column(type: 'integer', nullable: true)]
    private $participation;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $rigueur;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $progression;

    // Getters et setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInscription(): ?Inscription
    {
        return $this->inscription;
    }

    public function setInscription(Inscription $inscription): self
    {
        $this->inscription = $inscription;
        return $this;
    }

    public function getParticipation(): ?int
    {
        return $this->participation;
    }

    public function setParticipation(?int $participation): self
    {
        $this->participation = $participation;
        return $this;
    }

    public function getRigueur(): ?int
    {
        return $this->rigueur;
    }

    public function setRigueur(?int $rigueur): self
    {
        $this->rigueur = $rigueur;
        return $this;
    }

    public function getProgression(): ?int
    {
        return $this->progression;
    }

    public function setProgression(?int $progression): self
    {
        $this->progression = $progression;
        return $this;
    }
}

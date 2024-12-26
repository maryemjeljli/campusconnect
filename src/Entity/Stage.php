<?php

namespace App\Entity;
use App\Entity\Typestage; 
use App\Repository\StageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StageRepository::class)]
class Stage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $titre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $domaine = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $entreprise = null;

    

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'La localisation ne peut pas être vide.')]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9 .]+$/',
        message: 'La localisation ne peut contenir que des lettres, chiffres, espaces et des points.'
    )]
    private ?string $localisation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datededebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datedefin = null;

    #[ORM\ManyToOne(inversedBy: 'stages')]
    private ?Typestage $typestage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(?string $domaine): static
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getEntreprise(): ?string
    {
        return $this->entreprise;
    }

    public function setEntreprise(?string $entreprise): static
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getDatededebut(): ?\DateTimeInterface
    {
        return $this->datededebut;
    }

    public function setDatededebut(?\DateTimeInterface $datededebut): static
    {
        $this->datededebut = $datededebut;

        return $this;
    }

    public function getDatedefin(): ?\DateTimeInterface
    {
        return $this->datedefin;
    }

    public function setDatedefin(?\DateTimeInterface $datedefin): static
    {
        $this->datedefin = $datedefin;

        return $this;
    }

    public function getTypestage(): ?typestage
    {
        return $this->typestage;
    }

    public function setTypestage(?typestage $typestage): static
    {
        $this->typestage = $typestage;

        return $this;
    }
}

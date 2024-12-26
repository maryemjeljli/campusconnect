<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom de la formation est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La description de la formation est obligatoire.")]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lieu = null; // Non obligatoire, donc aucune validation.

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "La date de début est obligatoire.")]
    #[Assert\Type(\DateTimeInterface::class, message: "La date doit être valide.")]
    #[Assert\GreaterThanOrEqual(
        "today",
        message: "La date de début ne peut pas être antérieure à aujourd'hui."
    )]
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "La date de fin est obligatoire.")]
    #[Assert\Type(\DateTimeInterface::class, message: "La date doit être valide.")]
    #[Assert\GreaterThan(
        propertyPath: "date_debut",
        message: "La date de fin doit être postérieure à la date de début."
    )]
    private ?\DateTimeInterface $date_fin = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le coût est obligatoire.")]
    #[Assert\PositiveOrZero(message: "Le coût doit être supérieur ou égal à 0.")]
    private ?float $cout = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le formateur est obligatoire.")]
    private ?string $formateur = null;

    #[ORM\ManyToOne(inversedBy: 'formations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "Le type de formation est obligatoire.")]
    private ?TypeFormation $typeformation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: "integer")]
    #[Assert\NotBlank(message: "Le nombre de places est obligatoire.")]
    #[Assert\PositiveOrZero(message: "Le nombre de places doit être supérieur ou égal à 0.")]
    #[Assert\GreaterThanOrEqual(10, message: "Le nombre de places doit être au moins 10.")]
    #[Assert\LessThanOrEqual(20, message: "Le nombre de places ne peut pas dépasser 20.")]
    private ?int $places = null;

    // Getters et setters
    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): static
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getCout(): ?float
    {
        return $this->cout;
    }

    public function setCout(float $cout): static
    {
        $this->cout = $cout;

        return $this;
    }

    public function getFormateur(): ?string
    {
        return $this->formateur;
    }

    public function setFormateur(string $formateur): static
    {
        $this->formateur = $formateur;

        return $this;
    }

    public function getTypeformation(): ?TypeFormation
    {
        return $this->typeformation;
    }

    public function setTypeformation(?TypeFormation $typeformation): static
    {
        $this->typeformation = $typeformation;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getPlaces(): ?int
    {
        return $this->places;
    }

    public function setPlaces(int $places): static
    {
        $this->places = $places;

        return $this;
    }
}

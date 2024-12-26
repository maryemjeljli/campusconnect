<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Le titre de l'événement est obligatoire.")]
    private ?string $titre = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: "La description est obligatoire.")]
    private ?string $description = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(message: "La date de début est obligatoire.")]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(message: "La date de fin est obligatoire.")]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\ManyToOne(targetEntity: TypeEvenement::class, inversedBy: 'evenements')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?TypeEvenement $type = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\File(
        maxSize: "2M",
        mimeTypes: ["image/jpeg", "image/png", "image/gif"],
        mimeTypesMessage: "Veuillez uploader un fichier valide (JPG, PNG ou GIF)."
    )]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'evenement', targetEntity: InscriptionEvenement::class, cascade: ['persist', 'remove'])]
    private Collection $inscriptions;

    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
    }

    // Getters et Setters
    public function getId(): ?int { return $this->id; }

    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $titre): self { $this->titre = $titre; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): self { $this->description = $description; return $this; }

    public function getDateDebut(): ?\DateTimeInterface { return $this->dateDebut; }
    public function setDateDebut(\DateTimeInterface $dateDebut): self { $this->dateDebut = $dateDebut; return $this; }

    public function getDateFin(): ?\DateTimeInterface { return $this->dateFin; }
    public function setDateFin(\DateTimeInterface $dateFin): self { $this->dateFin = $dateFin; return $this; }

    public function getType(): ?TypeEvenement { return $this->type; }
    public function setType(?TypeEvenement $type): self { $this->type = $type; return $this; }

    public function getImage(): ?string { return $this->image; }
    public function setImage(?string $image): self { $this->image = $image; return $this; }

    public function getInscriptions(): Collection { return $this->inscriptions; }

    public function addInscription(InscriptionEvenement $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions[] = $inscription;
            $inscription->setEvenement($this);
        }

        return $this;
    }

    public function removeInscription(InscriptionEvenement $inscription): self
    {
        if ($this->inscriptions->removeElement($inscription)) {
            if ($inscription->getEvenement() === $this) {
                $inscription->setEvenement(null);
            }
        }

        return $this;
    }
}

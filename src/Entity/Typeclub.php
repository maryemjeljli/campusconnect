<?php

namespace App\Entity;
use App\Form\FormulaireTypeclubType;
use App\Repository\TypeclubRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: TypeclubRepository::class)]
class Typeclub
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: Club::class, cascade: ['remove'])]
   private Collection $clubs;

    public function __construct()
    {
        $this->clubs = new ArrayCollection(); // Initialize the collection
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }
    public function __toString(): string
    {
        return $this->libelle ?? '';
    }
   
    /*public function getClubs(): Collection
    {
        return $this->clubs;
    }

  

    public function removeClub(Club $club): static
    {
        if ($this->clubs->removeElement($club)) {
            if ($club->getType() === $this) {
                $club->setType(null);
            }
        }

        return $this;
    }*/
}

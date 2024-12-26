<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MembershipRepository;

#[ORM\Entity(repositoryClass: MembershipRepository::class)]
class Membership
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    // Informations sur l'utilisateur (étudiant)
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $student;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $studentEmail;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private $phoneNumber;

    // Informations sur le club
    #[ORM\ManyToOne(targetEntity: Club::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $club;

    #[ORM\Column(type: 'datetime')]
    private $joinedAt;

   

    // Getters et setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?User
    {
        return $this->student;
    }

    public function setStudent(?User $student): self
    {
        $this->student = $student;
        return $this;
    }

    public function getStudentEmail(): ?string
    {
        return $this->studentEmail;
    }

    public function setStudentEmail(?string $studentEmail): self
    {
        $this->studentEmail = $studentEmail;
        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): self
    {
        $this->club = $club;
        return $this;
    }

    public function getJoinedAt(): ?\DateTimeInterface
    {
        return $this->joinedAt;
    }

    public function setJoinedAt(\DateTimeInterface $joinedAt): self
    {
        $this->joinedAt = $joinedAt;
        return $this;
    }
}

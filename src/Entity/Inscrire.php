<?php

namespace App\Entity;

use App\Repository\InscrireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InscrireRepository::class)]
class Inscrire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $dateInscription;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'inscrires')]
    #[ORM\JoinColumn(nullable: false)]
    private $utilisateurSortie;

    #[ORM\ManyToOne(targetEntity: Sortie::class, inversedBy: 'sortieInscrires')]
    #[ORM\JoinColumn(nullable: false)]
    private $sortieUtilisateur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeInterface $dateInscription): self
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    public function getUtilisateurSortie(): ?User
    {
        return $this->utilisateurSortie;
    }

    public function setUtilisateurSortie(?User $utilisateurSortie): self
    {
        $this->utilisateurSortie = $utilisateurSortie;

        return $this;
    }

    public function getSortieUtilisateur(): ?Sortie
    {
        return $this->sortieUtilisateur;
    }

    public function setSortieUtilisateur(?Sortie $sortieUtilisateur): self
    {
        $this->sortieUtilisateur = $sortieUtilisateur;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SortieRepository::class)]
class Sortie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Assert\NotBlank(message:"Donner un nom explicite à votre sortie.")]
    #[ORM\Column(type: 'string', length: 255)]
    private $nom;

    #[Assert\GreaterThan (value: "today",message: "la sortie doit avoir lieu plus tard que maintenant!")]
    #[Assert\NotBlank(message:"A quelle heure commence votre sortie?")]
    #[ORM\Column(type: 'datetime')]
    private $dateHeureDebut;

    #[Assert\NotBlank(message:"Indiquez la durée de la sortie en minutes.")]
    #[ORM\Column(type: 'integer')]
    private $duree;

    #[Assert\Expression("this.getdateLimiteInscription() < this.getDateHeureDebut()",
        message:"La date d'inscription doit être antérieure à la date début.")]
    #[Assert\NotBlank(message:"Indiquez la date limite pour s'incrire à votre sortie.")]
    #[ORM\Column(type: 'datetime')]
    private $dateLimiteInscription;

    #[Assert\NotBlank(message:"Indiquez le nombre maximum de participants.")]
    #[ORM\Column(type: 'integer')]
    private $nbInscriptionMax;

    #[Assert\Length(max:"1000", maxMessage: "Trop long, maximum 1000 caractères.")]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $infosSortie;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $organisateur;

    #[ORM\ManyToOne(targetEntity: Lieu::class, inversedBy: 'sortie')]
    #[ORM\JoinColumn(nullable: false)]
    private $lieu;

    #[ORM\ManyToOne(targetEntity: Etat::class, cascade: ["persist"], inversedBy: 'sortie')]
    #[ORM\JoinColumn(nullable: false)]
    private $etat;

    #[ORM\ManyToOne(targetEntity: Site::class, inversedBy: 'sortie')]
    #[ORM\JoinColumn(nullable: false)]
    private $site;

    #[ORM\Column(type: 'boolean')]
    private $isPublished;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'estInscrit')]
    private $aEteInscrit;

    #[ORM\Column(type: 'string', length: 500, nullable: true)]
    private $motif;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $compteur;




    public function __construct()
    {
        $this->isPublished = true;
        $this->aEteInscrit = new ArrayCollection();
        $this->dateHeureDebut = new \DateTime();
        $this->dateLimiteInscription= new \DateTime();
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(\DateTimeInterface $dateLimiteInscription): self
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    public function getNbInscriptionMax(): ?int
    {
        return $this->nbInscriptionMax;
    }

    public function setNbInscriptionMax(int $nbInscriptionMax): self
    {
        $this->nbInscriptionMax = $nbInscriptionMax;

        return $this;
    }

    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    public function setInfosSortie(?string $infosSortie): self
    {
        $this->infosSortie = $infosSortie;

        return $this;
    }


    public function getOrganisateur(): ?string
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?string $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getAEteInscrit(): Collection
    {
        return $this->aEteInscrit;
    }

    public function addAEteInscrit(User $aEteInscrit): self
    {
        if (!$this->aEteInscrit->contains($aEteInscrit)) {
            $this->aEteInscrit[] = $aEteInscrit;
            $aEteInscrit->addEstInscrit($this);
        }

        return $this;
    }

    public function removeAEteInscrit(User $aEteInscrit): self
    {
        if ($this->aEteInscrit->removeElement($aEteInscrit)) {
            $aEteInscrit->removeEstInscrit($this);
        }

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getCompteur(): ?int
    {
        return $this->compteur;
    }

    public function setCompteur(?int $compteur): self
    {
        $this->compteur = $compteur;

        return $this;
    }




}

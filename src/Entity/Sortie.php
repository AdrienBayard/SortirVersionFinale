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

    #[ORM\Column(type: 'string', length: 255)]
    private $nom;

    #[ORM\Column(type: 'datetime')]
    private $dateHeureDebut;

    #[ORM\Column(type: 'integer')]
    private $duree;

    #[ORM\Column(type: 'datetime')]
    private $dateLimiteInscription;

    #[ORM\Column(type: 'integer')]
    private $nbInscriptionMax;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $infosSortie;

    #[ORM\Column(type: 'integer')]
    private $organisateur;

    #[ORM\ManyToOne(targetEntity: Lieu::class, inversedBy: 'sortie')]
    #[ORM\JoinColumn(nullable: false)]
    private $lieu;

    #[ORM\ManyToOne(targetEntity: Etat::class, inversedBy: 'sortie')]
    #[ORM\JoinColumn(nullable: false)]
    private $etat;

    #[ORM\ManyToOne(targetEntity: Site::class, inversedBy: 'sortie')]
    #[ORM\JoinColumn(nullable: false)]
    private $site;

    #[ORM\Column(type: 'boolean')]
    private $isPublished;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'estInscrit')]
    private $contientInscrit;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'estOrganisateur')]
    private $estOrganisePar;


    public function __construct()
    {
        $this->isPublished = true;
        $this->contientInscrit = new ArrayCollection();
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


    public function getOrganisateur(): ?int
    {
        return $this->organisateur;
    }

    public function setOrganisateur(int $organisateur): self
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
    public function getContientInscrit(): Collection
    {
        return $this->contientInscrit;
    }

    public function addContientInscrit(User $contientInscrit): self
    {
        if (!$this->contientInscrit->contains($contientInscrit)) {
            $this->contientInscrit[] = $contientInscrit;
            $contientInscrit->addEstInscrit($this);
        }

        return $this;
    }

    public function removeContientInscrit(User $contientInscrit): self
    {
        if ($this->contientInscrit->removeElement($contientInscrit)) {
            $contientInscrit->removeEstInscrit($this);
        }

        return $this;
    }

    public function getEstOrganisePar(): ?User
    {
        return $this->estOrganisePar;
    }

    public function setEstOrganisePar(?User $estOrganisePar): self
    {
        $this->estOrganisePar = $estOrganisePar;

        return $this;
    }


}

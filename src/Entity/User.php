<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity(fields={"pseudo"}, message="There is already an account with this pseudo")
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $pseudo;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $nom;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $prenom;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $telephone;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $mail;

    #[ORM\Column(type: 'boolean')]
    private $actif;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $photo;

    #[ORM\Column(type: 'boolean')]
    private $premiereconnexion;

    #[ORM\ManyToOne(targetEntity: Site::class, inversedBy: 'user')]
    #[ORM\JoinColumn(nullable: false)]
    private $site;

    #[ORM\ManyToMany(targetEntity: Sortie::class, inversedBy: 'contientInscrit')]
    private $estInscrit;

    #[ORM\OneToMany(mappedBy: 'estOrganisePar', targetEntity: Sortie::class)]
    private $estOrganisateur;

    public function __construct()
    {
        $this->estInscrit = new ArrayCollection();
        $this->estOrganisateur = new ArrayCollection();
    }





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(?bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getPremiereconnexion(): ?bool
    {
        return $this->premiereconnexion;
    }

    public function setPremiereconnexion(bool $premiereconnexion): self
    {
        $this->premiereconnexion = $premiereconnexion;

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



/*    public function __toString(): string
    {
        return $this->id.''.$this->roles.''.$this->nom.''.$this->prenom.''.$this->password.''.$this->mail.''.$this->pseudo.''.$this->telephone.''.$this->actif.''.$this->photo.''.$this->premiereconnexion;
    }*/

/**
 * @return Collection|Sortie[]
 */
public function getEstInscrit(): Collection
{
    return $this->estInscrit;
}

public function addEstInscrit(Sortie $estInscrit): self
{
    if (!$this->estInscrit->contains($estInscrit)) {
        $this->estInscrit[] = $estInscrit;
    }

    return $this;
}

public function removeEstInscrit(Sortie $estInscrit): self
{
    $this->estInscrit->removeElement($estInscrit);

    return $this;
}

/**
 * @return Collection|Sortie[]
 */
public function getEstOrganisateur(): Collection
{
    return $this->estOrganisateur;
}

public function addEstOrganisateur(Sortie $estOrganisateur): self
{
    if (!$this->estOrganisateur->contains($estOrganisateur)) {
        $this->estOrganisateur[] = $estOrganisateur;
        $estOrganisateur->setEstOrganisePar($this);
    }

    return $this;
}

public function removeEstOrganisateur(Sortie $estOrganisateur): self
{
    if ($this->estOrganisateur->removeElement($estOrganisateur)) {
        // set the owning side to null (unless already changed)
        if ($estOrganisateur->getEstOrganisePar() === $this) {
            $estOrganisateur->setEstOrganisePar(null);
        }
    }

    return $this;
}


}

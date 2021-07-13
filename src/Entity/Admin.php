<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 *
 * @ApiResource( normalizationContext={"groups"={"read"}}  ,
 *  denormalizationContext={"groups"={"write"}} , formats={"json"}
 * )
 */
class Admin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"read" , "write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"read" , "write"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"read" , "write"})
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Publicite::class, mappedBy="idAdmin")
     */
    private $publicites;

    /**
     * @ORM\OneToMany(targetEntity=Film::class, mappedBy="idAdmin")
     */
    private $films;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="idAdmin")
     */
    private $commentaires;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups ({"read" , "write"})
     */
    private $nom;

    public function __construct()
    {
        $this->publicites = new ArrayCollection();
        $this->films = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function __toString()
    {
        return(String) $this->getNom();
    }

    /**
     * @return Collection|Publicite[]
     */
    public function getPublicites(): Collection
    {
        return $this->publicites;
    }

    public function addPublicite(Publicite $publicite): self
    {
        if (!$this->publicites->contains($publicite)) {
            $this->publicites[] = $publicite;
            $publicite->setIdAdmin($this);
        }

        return $this;
    }

    public function removePublicite(Publicite $publicite): self
    {
        if ($this->publicites->removeElement($publicite)) {
            // set the owning side to null (unless already changed)
            if ($publicite->getIdAdmin() === $this) {
                $publicite->setIdAdmin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Film[]
     */
    public function getFilms(): Collection
    {
        return $this->films;
    }

    public function addFilm(Film $film): self
    {
        if (!$this->films->contains($film)) {
            $this->films[] = $film;
            $film->setIdAdmin($this);
        }

        return $this;
    }

    public function removeFilm(Film $film): self
    {
        if ($this->films->removeElement($film)) {
            // set the owning side to null (unless already changed)
            if ($film->getIdAdmin() === $this) {
                $film->setIdAdmin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setIdAdmin($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getIdAdmin() === $this) {
                $commentaire->setIdAdmin(null);
            }
        }

        return $this;
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
}

<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @ORM\Entity(repositoryClass=FilmRepository::class)
 *
 * @ApiResource( normalizationContext={"groups"={"read"}}  ,
 *  denormalizationContext={"groups"={"write"}} , formats={"json"})
 *
 */

class Film
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"write", "read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"write", "read"})
     */
    private $nomFilm;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"write" , "read"})
     */
    private $showTime;

    /**
     * @ORM\Column(type="time")
     * @Groups({"write" , "read"})
     */
    private $duree;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"write" , "read"})
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"write" , "read"})
     */
    private $audience;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="films")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"write" , "read"})
     */
    private $idCategorie;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="idFilm")
     */
    private $reservations;

    /**
     * @ORM\ManyToOne(targetEntity=Admin::class, inversedBy="films")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"write" , "read"})
     */
    private $idAdmin;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="idFilm")
     */
    private $commentaires;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFilm(): ?string
    {
        return $this->nomFilm;
    }

    public function setNomFilm(string $nomFilm): self
    {
        $this->nomFilm = $nomFilm;

        return $this;
    }

    public function getShowTime(): ?\DateTimeInterface
    {
        return $this->showTime;
    }

    public function setShowTime(\DateTimeInterface $showTime): self
    {
        $this->showTime = $showTime;

        return $this;
    }

    public function getDuree(): ?\DateTimeInterface
    {
        return $this->duree;
    }

    public function setDuree(\DateTimeInterface $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getAudience(): ?string
    {
        return $this->audience;
    }

    public function setAudience(string $audience): self
    {
        $this->audience = $audience;

        return $this;
    }

    public function getIdCategorie(): ?Categorie
    {
        return $this->idCategorie;
    }

    public function setIdCategorie(Categorie $idCategorie): self
    {
        $this->idCategorie = $idCategorie;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setIdFilm($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getIdFilm() === $this) {
                $reservation->setIdFilm(null);
            }
        }

        return $this;
    }

    public function getIdAdmin(): ?Admin
    {
        return $this->idAdmin;
    }

    public function setIdAdmin(?Admin $idAdmin): self
    {
        $this->idAdmin = $idAdmin;

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
            $commentaire->setIdFilm($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getIdFilm() === $this) {
                $commentaire->setIdFilm(null);
            }
        }

        return $this;
    }
}

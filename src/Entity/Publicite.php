<?php

namespace App\Entity;

use App\Repository\PubliciteRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=PubliciteRepository::class)
 */

#[ApiResource]
class Publicite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Cinema::class, inversedBy="publicites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_cinema;


    /**
     * @ORM\ManyToOne(targetEntity=Admin::class, inversedBy="publicites")
     * @ORM\JoinColumn(nullable=true)
     */
    private $id_admin;

    /**
     * @ORM\Column(type="date")
     */
    private $datefin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $etat;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIdCinema(): ?Cinema
    {
        return $this->id_cinema;
    }

    public function setIdCinema(?Cinema $id_cinema): self
    {
        $this->id_cinema = $id_cinema;

        return $this;
    }

    public function getIdAdmin(): ?Admin
    {
        return $this->id_admin;
    }

    public function setIdAdmin(?Admin $id_admin): self
    {
        $this->id_admin = $id_admin;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDateFin(\DateTimeInterface $date): self
    {
        $this->datefin = $date;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
}

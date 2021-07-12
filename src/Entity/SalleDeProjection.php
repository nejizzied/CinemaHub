<?php

namespace App\Entity;

use App\Repository\SalleDeProjectionRepository;
use Doctrine\ORM\Mapping as ORM;use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource( normalizationContext={"groups"={"read"}}  ,
 *  denormalizationContext={"groups"={"write"}} , formats={"json"}
 * )
 * @ORM\Entity(repositoryClass=SalleDeProjectionRepository::class)
 */

 
#[ApiResource]
class SalleDeProjection
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
    private $nbr_places;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Cinema::class, inversedBy="salleDeProjections")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_cinema;

    /**
     * @ORM\OneToOne(targetEntity=Film::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_film;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrPlaces(): ?int
    {
        return $this->nbr_places;
    }

    public function setNbrPlaces(int $nbr_places): self
    {
        $this->nbr_places = $nbr_places;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

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

    public function getIdFilm(): ?Film
    {
        return $this->id_film;
    }

    public function setIdFilm(Film $id_film): self
    {
        $this->id_film = $id_film;

        return $this;
    }
}

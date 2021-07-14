<?php

namespace App\Entity;

use App\Repository\SalleDeProjectionRepository;
use Doctrine\ORM\Mapping as ORM;use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @ORM\Entity(repositoryClass=SalleDeProjectionRepository::class)
 *
 * @ApiResource(normalizationContext={"groups"={"read"}}  ,
 *  denormalizationContext={"groups"={"write"}} , formats={"json"}
 * )
 */


class SalleDeProjection
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("read")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"write" , "read"})
     */
    private $nbrPlaces;

    /**
     * @ORM\Column(type="string", length=255 , nullable=true)
     * @Groups({"read"})
     */
    private $image;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"write" , "read"})
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Cinema::class, inversedBy="salleDeProjections")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"write" , "read"})
     */
    private $idCinema;

    /**
     * @ORM\OneToOne(targetEntity=Film::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"write" , "read"})
     */
    private $idFilm;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrPlaces(): ?int
    {
        return $this->nbrPlaces;
    }

    public function setNbrPlaces(int $nbrPlaces): self
    {
        $this->nbrPlaces = $nbrPlaces;

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
        return $this->idCinema;
    }

    public function setIdCinema(?Cinema $idCinema): self
    {
        $this->idCinema = $idCinema;

        return $this;
    }

    public function getIdFilm(): ?Film
    {
        return $this->idFilm;
    }

    public function setIdFilm(Film $idFilm): self
    {
        $this->idFilm = $idFilm;

        return $this;
    }
}

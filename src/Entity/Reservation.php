<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * 
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 *
 * @ApiResource( normalizationContext={"groups"={"read"}}  ,
 *  denormalizationContext={"groups"={"write"}} , formats={"json"}
 * )
 *
 * 
 */

class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"write" , "read"})
     */
    private $nbrTickets;

    /**
     * @ORM\ManyToOne(targetEntity=Film::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"write" , "read"})
     */
    private $idFilm;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"write" , "read"})
     */
    private $idUser;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"write" , "read"})
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrTickets(): ?int
    {
        return $this->nbrTickets;
    }

    public function setNbrTickets(int $nbrTickets): self
    {
        $this->nbrTickets = $nbrTickets;

        return $this;
    }

    public function getIdFilm(): ?Film
    {
        return $this->idFilm;
    }

    public function setIdFilm(?Film $idFilm): self
    {
        $this->idFilm = $idFilm;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }
}

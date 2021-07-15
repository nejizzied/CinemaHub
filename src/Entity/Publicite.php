<?php

namespace App\Entity;

use App\Repository\PubliciteRepository;
use Doctrine\ORM\Mapping as ORM;use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

/**
 *
 * @ORM\Entity(repositoryClass=PubliciteRepository::class)
 *
 * @ApiResource( normalizationContext={"groups"={"read"}}  ,
 *  denormalizationContext={"groups"={"write"}} , formats={"json"} ,
 *     itemOperations = {
 *          "get",
 *          "put",
 *          "patch",
 *          "affichagePubCinema" = {
 *              "route_name" = "affichagePubCinema",
 *          },
 *     "AnnulerPub" = {
 *              "route_name" = "AnnulerPub",
 *          },
 *     "ConfirmerPub" = {
 *              "route_name" = "ConfirmerPub",
 *          },
 *     "DelPub" = {
 *              "route_name" = "DelPub",
 *          },
 *     "ModifierPub" = {
 *              "route_name" = "ModifierPub",
 *          },
 *     "AjoutPub" = {
 *              "route_name" = "AjoutPub",
 *          },
 *      "pub" = {
 *              "route_name" = "pub",
 *          },
 * }
 * )
 */

class Publicite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"write" , "read"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"write" , "read"})
     */
    private $prix;

    /**
     * @ORM\Column(type="date")
     * @Groups({"write" , "read"})
     */
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'])]
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Cinema::class, inversedBy="publicites")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"write" })
     */
    private $idCinema;


    /**
     * @ORM\ManyToOne(targetEntity=Admin::class, inversedBy="publicites")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"write"})
     */
    private $idAdmin;

    /**
     * @ORM\Column(type="date")
     * @Groups({"write" , "read"})
     */
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'])]
    private $datefin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"write" , "read"})
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
        return $this->idCinema;
    }

    public function setIdCinema(?Cinema $idCinema): self
    {
        $this->idCinema = $idCinema;

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

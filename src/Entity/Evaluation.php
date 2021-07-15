<?php

namespace App\Entity;

use App\Repository\EvaluationRepository;
use Doctrine\ORM\Mapping as ORM;use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @ORM\Entity(repositoryClass=EvaluationRepository::class)
 *
 * @ApiResource( normalizationContext={"groups"={"read"}}  ,
 *  denormalizationContext={"groups"={"write"}} , formats={"json"} ,
 *     itemOperations = {
 *      "get",
 *      "put",
 *      "patch",
 *      "evaluation_cinema" = {
 *          "method" = "get",
 *          "path" = "/evaluation/cinema/{id}",
 *          "controller" = EvaluationController::class,
 *          },
 *      }
 * )
 */
class Evaluation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"write" , "read"})
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity=Cinema::class, inversedBy="evaluations")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"write" , "read"})
     */
    private $idCinema;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;

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
}

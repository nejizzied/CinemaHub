<?php

namespace App\Entity;

use App\Repository\CinemaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 *
 * @ORM\Entity(repositoryClass=CinemaRepository::class)
 *
 * @Vich\Uploadable
 * @ApiResource( normalizationContext={"groups"={"read"}}  ,
 *  denormalizationContext={"groups"={"write"}} , formats={"json"} ,
 *        collectionOperations= {
 *                          "get",
 *                          "post" = {
 *                          "input_formats" = {
 *                               "multipart" = {"multipart/form-data"},
 *                          },
 *                          },
 *                          },
 * )
 */
class Cinema
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"write" , "read"})
     */
    private $nomCinema;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"write" , "read"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="string")
     * @Groups({"write" , "read"})
     */
    private $numTel;

    /**
     * @ORM\Column(type="string", length=255 , unique=true)
     * @Groups({"write" , "read"})
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"write" , "read"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({ "read"})
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="photo_de_cinema", fileNameProperty="image")
     * @var File
     *  @Groups ({"write"})
     */
    private $imageFile;



    /**
     * @ORM\OneToMany(targetEntity=SalleDeProjection::class, mappedBy="idCinema")
     *
     *
     */
    private $salleDeProjections;

    /**
     * @ORM\OneToMany(targetEntity=Publicite::class, mappedBy="idCinema")
     */
    private $publicites;

    /**
     * @ORM\OneToMany(targetEntity=Evaluation::class, mappedBy="idCinema", orphanRemoval=true)
     * @Groups({"read"})
     */
    private $evaluations;

    public function __construct()
    {
        $this->salleDeProjections = new ArrayCollection();
        $this->publicites = new ArrayCollection();
        $this->evaluations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCinema(): ?string
    {
        return $this->nomCinema;
    }

    public function setNomCinema(string $nomCinema): self
    {
        $this->nomCinema = $nomCinema;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNumTel(): ?int
    {
        return $this->numTel;
    }

    public function setNumTel(int $numTel): self
    {
        $this->numTel = $numTel;

        return $this;
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->email = $this->getEmail();

        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function __toString()
    {
        return(String) $this->getNomCinema();
    }

    /**
     * @return Collection|SalleDeProjection[]
     */
    public function getSalleDeProjections(): Collection
    {
        return $this->salleDeProjections;
    }

    public function addSalleDeProjection(SalleDeProjection $salleDeProjection): self
    {
        if (!$this->salleDeProjections->contains($salleDeProjection)) {
            $this->salleDeProjections[] = $salleDeProjection;
            $salleDeProjection->setIdCinema($this);
        }

        return $this;
    }

    public function removeSalleDeProjection(SalleDeProjection $salleDeProjection): self
    {
        if ($this->salleDeProjections->removeElement($salleDeProjection)) {
            // set the owning side to null (unless already changed)
            if ($salleDeProjection->getIdCinema() === $this) {
                $salleDeProjection->setIdCinema(null);
            }
        }

        return $this;
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
            $publicite->setIdCinema($this);
        }

        return $this;
    }

    public function removePublicite(Publicite $publicite): self
    {
        if ($this->publicites->removeElement($publicite)) {
            // set the owning side to null (unless already changed)
            if ($publicite->getIdCinema() === $this) {
                $publicite->setIdCinema(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Evaluation[]
     */
    public function getEvaluations(): Collection
    {
        return $this->evaluations;
    }

    public function addEvaluation(Evaluation $evaluation): self
    {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations[] = $evaluation;
            $evaluation->setIdCinema($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): self
    {
        if ($this->evaluations->removeElement($evaluation)) {
            // set the owning side to null (unless already changed)
            if ($evaluation->getIdCinema() === $this) {
                $evaluation->setIdCinema(null);
            }
        }

        return $this;
    }
}

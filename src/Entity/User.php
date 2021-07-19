<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Annotation\Context;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
/**
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @Vich\Uploadable
 * @ApiResource( normalizationContext={"groups"={"read"}}  ,
 *  denormalizationContext={"groups"={"write"}} , formats={"json"} ,
 *     collectionOperations= {
 *                          "get",
 *                          "post" = {
 *                          "input_formats" = {
 *                               "multipart" = {"multipart/form-data"},
 *                          },
 *                          },
 *                          },
 * )
 */

#[ApiFilter(SearchFilter::class, properties: ['email' => 'exact' , 'password' => 'exact'])]
class User
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
     *  @Groups ({"read" , "write"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups ({"read" , "write"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="date")
     *  @Groups ({"read" , "write"})
     *
     */
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'])]
    private $dateDeNes;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups ({"read" , "write"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255 , unique=true)
     *  @Groups ({"read" , "write"})
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=9999)
     *  @Groups ({"read" })
     */
    private $photoDeProfile;

    /**
     * @Vich\UploadableField(mapping="photo_de_profile", fileNameProperty="photoDeProfile")
     * @var File
     *  @Groups ({"write"})
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups ({"read" , "write"})
     */
    private $role;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *  @Groups ({"read" })
     */
    private $pointFidelite;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="idUser")
     */
    private $reservations;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="idUser")
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateDeNes(): ?\DateTimeInterface
    {
        return $this->dateDeNes;
    }

    public function setDateDeNes(\DateTimeInterface $dateDeNes): self
    {
        $this->dateDeNes = $dateDeNes;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhotoDeProfile(): ?string
    {
        return $this->photoDeProfile;
    }

    public function setPhotoDeProfile(string $photoDeProfile): self
    {
        $this->photoDeProfile = $photoDeProfile;

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
            $this->pointFidelite = 5;

        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }


    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getPointFidelite(): ?int
    {
        return $this->pointFidelite;
    }

    public function setPointFidelite(?int $pointFidelite): self
    {
        $this->pointFidelite = $pointFidelite;

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
            $reservation->setIdUser($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getIdUser() === $this) {
                $reservation->setIdUser(null);
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
            $commentaire->setIdUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getIdUser() === $this) {
                $commentaire->setIdUser(null);
            }
        }

        return $this;
    }
}

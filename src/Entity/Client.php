<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 *  @UniqueEntity(
 *  fields= {"email"},
 *  message= "L'email que vous avez indiqué est déjà utilisé !"
 * )
 */
class Client
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list","detail"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list","detail"})
     */
    private $firstName;

    /**
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Groups({"list", "detail"})
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"detail"})
     */
    private $dateCreated;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="clients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Phone", mappedBy="client")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $phones;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"list"})
     */
    private $numberOfPhone;

    public function __construct()
    {
        $this->phones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

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

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Phone[]
     */
    public function getPhones(): Collection
    {
        return $this->phones;
    }

    public function addPhone(Phone $phone): self
    {
        if (!$this->phones->contains($phone)) {
            $this->phones[] = $phone;
            $phone->setClient($this);
        }

        return $this;
    }

    public function removePhone(Phone $phone): self
    {
        if ($this->phones->contains($phone)) {
            $this->phones->removeElement($phone);
            // set the owning side to null (unless already changed)
            if ($phone->getClient() === $this) {
                $phone->setClient(null);
            }
        }

        return $this;
    }

    public function getNumberOfPhone(): ?int
    {
        return $this->numberOfPhone;
    }

    public function setNumberOfPhone(int $numberOfPhone): self
    {
        $this->numberOfPhone = $numberOfPhone;

        return $this;
    }
}

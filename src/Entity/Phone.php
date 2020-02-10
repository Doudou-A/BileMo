<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\PhoneRepository")
 *  @UniqueEntity(
 *  fields= {"serialNumber"},
 *  message= "Le numéro de série que vous avez indiqué est déjà utilisé !"
 * )
 * 
 * @Hateoas\Relation(
 *     "create",
 *     href=@Hateoas\Route(
 *          "phone_create"
 *     )
 * )
 */
class Phone
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"list", "detail"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list", "detail"})
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Groups({"detail"})
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"detail"})
     */
    private $availability;

    /**
     * @ORM\Column(type="integer", unique=true)
     * @Groups({"list", "detail"})
     */
    private $serialNumber;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"list", "detail"})
     */
    private $dateCreated;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="phones")
     * @ORM\JoinColumn(nullable=true)
     */
    private $client;
    
    /**
     * @Groups({"detail"})
     */
    private $links;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getAvailability(): ?bool
    {
        return $this->availability;
    }

    public function setAvailability(bool $availability): self
    {
        $this->availability = $availability;

        return $this;
    }

    public function getSerialNumber(): ?int
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(int $serialNumber): self
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function setLinks($links)
    {
        $this->links = $links;

        return $this;
    }

}

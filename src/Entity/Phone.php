<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhoneRepository")
 */
class Phone
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"list", "show"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list", "show"})
     * @Assert\NotBlank(message="Le champs ne doit pas être vide")
     * @Assert\Length(min="2", minMessage="le nom doit avoir au moins {{ limit }} caractères", max="255", maxMessage="le nom doit avoir au maximum {{ limit }} caractères")
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"list", "show"})
     * @Assert\NotBlank(message="Le champs ne doit pas être vide")
     * @Assert\Range(min="0", max="2000", minMessage="la valeur minimum autorisée est {{ limit }}", maxMessage="la valeur maximal autorisée est {{ limit }}"))
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show"})
     */
    private $color;

    /**
     * @ORM\Column(type="text")
     * @Groups({"show"})
     */
    private $description;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}

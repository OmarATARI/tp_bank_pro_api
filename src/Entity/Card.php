<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CardRepository")
 */
class Card
{
    /**
     * @Groups("userProfile")
     * @Groups("cardIndex")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups("userIndex")
     * @Groups("userProfile")
     * @Groups("cardIndex")
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Groups("userProfile")
     * @Groups("cardIndex")
     * @ORM\Column(type="string", length=255)
     */
    private $creditCardType;

    /**
     * @Groups("userProfile")
     * @Groups("cardIndex")
     * @ORM\Column(type="string")
     */
    private $creditCardNumber;

    /**
     * @Groups("userProfile")
     * @Groups("cardIndex")
     * @ORM\Column(type="string", length=255)
     */
    private $currencyCode;

    /**
     * @Groups("userProfile")
     * @Groups("cardIndex")
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @Groups("cardIndex")
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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

    public function getCreditCardType(): ?string
    {
        return $this->creditCardType;
    }

    public function setCreditCardType(string $creditCardType): self
    {
        $this->creditCardType = $creditCardType;

        return $this;
    }

    public function getCreditCardNumber(): ?string
    {
        return $this->creditCardNumber;
    }

    public function setCreditCardNumber(int $creditCardNumber): self
    {
        $this->creditCardNumber = $creditCardNumber;

        return $this;
    }

    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode(string $currencyCode): self
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

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
}

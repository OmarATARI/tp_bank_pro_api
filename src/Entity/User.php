<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    /**
     * @Groups("userProfile")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups("userIndex")
     * @Groups("userProfile")
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=0, max=10)
     */
    private $firstName;

    /**
     * @Groups("userProfile")
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $lastName;

    /**
     * @Groups("userIndex")
     * @Groups("userProfile")
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @Groups("userProfile")
     * @ORM\Column(type="string", length=255)
     */
    private $apiKey;

    /**
     * @Groups("userProfile")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @Groups("userProfile")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @Groups("userProfile")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;


    /**
     * @Groups("userProfile")
     * @Groups("userIndex")
     * @ORM\OneToMany(targetEntity="App\Entity\Card", mappedBy="user", orphanRemoval=true, cascade={"persist"})
     */
    private $cards;

    /**
     * @Groups("userProfile")
     * @Groups("userIndex")
     * @ORM\ManyToOne(targetEntity="App\Entity\Subscription", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $subscription;

    /**
     * @var int $id_subscription
     * @ORM\Column(type="integer", nullable=false)
     */
    private $subscription_id;

    /**
     * @Groups("userProfile")
     * @ORM\Column(type="simple_array")
     */
    private $roles = [];



    public function __construct()
    {
        $this->roles = array('ROLE_USER');
        $this->createdAt = new \DateTime();
        $this->cards = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

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

    /**
     * @return string|null
     * @Assert\NotBlank()
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    /**
     * @param mixed $subscription
     */
    public function setSubscription($subscription): void
    {
        $this->subscription = $subscription;
    }

    /**
     * @return int
     */
    public function getSubscriptionId(): int
    {
        return $this->subscription_id;
    }

    /**
     * @param int $id_subscription
     */
    public function setSubscriptionId(int $id_subscription): void
    {
        $this->subscription_id = $id_subscription;
    }

    /**
     * @return Collection|Card[]
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setUser($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->contains($card)) {
            $this->cards->removeElement($card);
            // set the owning side to null (unless already changed)
            if ($card->getUser() === $this) {
                $card->setUser(null);
            }
        }

        return $this;
    }


    // ================================== USERINTERFACE METHODS

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }


    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

}

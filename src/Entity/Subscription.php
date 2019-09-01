<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubscriptionRepository")
 */
class Subscription
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("userIndex")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("userIndex")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("userIndex")
     */
    private $slogan;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("userIndex")
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups("userIndex")
     */
    private $slug;


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

    public function getSlogan(): ?string
    {
        return $this->slogan;
    }

    public function setSlogan(string $slogan): self
    {
        $this->slogan = $slogan;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }
}

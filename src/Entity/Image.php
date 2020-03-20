<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PortofolioPage", inversedBy="natureGallery", cascade={"persist"})
     */
    private $natureGallery;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PortofolioPage", inversedBy="eventGallery")
     */
    private $eventGallery;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PortofolioPage", inversedBy="showGallery")
     */
    private $showGallery;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PortofolioPage", inversedBy="sesionGallery")
     */
    private $sesionGallery;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
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

    public function getNatureGallery(): ?PortofolioPage
    {
        return $this->natureGallery;
    }

    public function setNatureGallery(?PortofolioPage $natureGallery): self
    {
        $this->natureGallery = $natureGallery;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getEventGallery(): ?PortofolioPage
    {
        return $this->eventGallery;
    }

    public function setEventGallery(?PortofolioPage $eventGallery): self
    {
        $this->eventGallery = $eventGallery;

        return $this;
    }

    public function getShowGallery(): ?PortofolioPage
    {
        return $this->showGallery;
    }

    public function setShowGallery(?PortofolioPage $showGallery): self
    {
        $this->showGallery = $showGallery;

        return $this;
    }

    public function getSesionGallery(): ?PortofolioPage
    {
        return $this->sesionGallery;
    }

    public function setSesionGallery(?PortofolioPage $sesionGallery): self
    {
        $this->sesionGallery = $sesionGallery;

        return $this;
    }
}

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
    private $portofolioPage;

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

    public function getPortofolioPage(): ?PortofolioPage
    {
        return $this->portofolioPage;
    }

    public function setPortofolioPage(?PortofolioPage $portofolioPage): self
    {
        $this->portofolioPage = $portofolioPage;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}

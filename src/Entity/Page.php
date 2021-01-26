<?php

namespace App\Entity;

use App\Repository\PageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PageRepository::class)
 */
class Page
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Navbar::class, inversedBy="pages")
     */
    private $navbar;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive()
     */
    private $position;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $titleEn;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $titleEs;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $titleCa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getNavbar(): ?Navbar
    {
        return $this->navbar;
    }

    public function setNavbar(?Navbar $navbar): self
    {
        $this->navbar = $navbar;

        return $this;
    }

    public function __toString()
    {
        return $this->getSlug();
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }
    public function getTitleEn(): ?string
    {
        return $this->titleEn;
    }

    public function setTitleEn(string $titleEn): self
    {
        $this->titleEn = $titleEn;

        return $this;
    }

    public function getTitleEs(): ?string
    {
        return $this->titleEs;
    }

    public function setTitleEs(string $titleEs): self
    {
        $this->titleEs = $titleEs;

        return $this;
    }

    public function getTitleCa(): ?string
    {
        return $this->titleCa;
    }

    public function setTitleCa(string $titleCa): self
    {
        $this->titleCa = $titleCa;

        return $this;
    }
}

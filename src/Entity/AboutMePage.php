<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AboutMePageRepository")
 */
class AboutMePage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $text_es;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $text_ca;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $text_en;

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

    public function getTextEs(): ?string
    {
        return $this->text_es;
    }

    public function setTextEs(?string $text_es): self
    {
        $this->text_es = $text_es;

        return $this;
    }

    public function getTextCa(): ?string
    {
        return $this->text_ca;
    }

    public function setTextCa(?string $text_ca): self
    {
        $this->text_ca = $text_ca;

        return $this;
    }

    public function getTextEn(): ?string
    {
        return $this->text_en;
    }

    public function setTextEn(?string $text_en): self
    {
        $this->text_en = $text_en;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
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
    private $titleEn;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $titleEs;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $titleCa;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contentEn;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contentEs;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contentCa;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageFileName;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContentEn(): ?string
    {
        return $this->contentEn;
    }

    public function setContentEn(?string $contentEn): self
    {
        $this->contentEn = $contentEn;

        return $this;
    }

    public function getContentEs(): ?string
    {
        return $this->contentEs;
    }

    public function setContentEs(?string $contentEs): self
    {
        $this->contentEs = $contentEs;

        return $this;
    }

    public function getContentCa(): ?string
    {
        return $this->contentCa;
    }

    public function setContentCa(?string $contentCa): self
    {
        $this->contentCa = $contentCa;

        return $this;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function __toString()
    {
        return $this->getSlug();
    }

    public function getImageFileName(): ?string
    {
        return $this->imageFileName;
    }

    public function setImageFileName(?string $imageFileName): self
    {
        $this->imageFileName = $imageFileName;

        return $this;
    }


}

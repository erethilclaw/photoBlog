<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PortofolioPageRepository")
 */
class PortofolioPage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="portofolioPage", cascade={"persist"})
     */
    private $natureGallery;

    public function __construct()
    {
        $this->natureGallery = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Image[]
     */
    public function getNatureGallery(): Collection
    {
        return $this->natureGallery;
    }

    public function addNatureGallery(Image $natureGallery): self
    {
        if (!$this->natureGallery->contains($natureGallery)) {
            $this->natureGallery[] = $natureGallery;
            $natureGallery->setPortofolioPage($this);
        }

        return $this;
    }

    public function removeNatureGallery(Image $natureGallery): self
    {
        if ($this->natureGallery->contains($natureGallery)) {
            $this->natureGallery->removeElement($natureGallery);
            // set the owning side to null (unless already changed)
            if ($natureGallery->getPortofolioPage() === $this) {
                $natureGallery->setPortofolioPage(null);
            }
        }

        return $this;
    }
}

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
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="natureGallery", cascade={"persist"})
     */
    private $natureGallery;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="eventGallery", cascade={"persist"})
     */
    private $eventGallery;

    public function __construct()
    {
        $this->natureGallery = new ArrayCollection();
        $this->eventGallery = new ArrayCollection();
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
            $natureGallery->setNatureGallery($this);
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

    public function removeAllNatureGallery(){
        $natureGallery = $this->getNatureGallery();
        foreach ($natureGallery as $image){
            $this->removeNatureGallery($image);
        }
    }

    /**
     * @return Collection|Image[]
     */
    public function getEventGallery(): Collection
    {
        return $this->eventGallery;
    }

    public function addEventGallery(Image $eventGallery): self
    {
        if (!$this->eventGallery->contains($eventGallery)) {
            $this->eventGallery[] = $eventGallery;
            $eventGallery->setEventGallery($this);
        }

        return $this;
    }

    public function removeEventGallery(Image $eventGallery): self
    {
        if ($this->eventGallery->contains($eventGallery)) {
            $this->eventGallery->removeElement($eventGallery);
            // set the owning side to null (unless already changed)
            if ($eventGallery->getEventGallery() === $this) {
                $eventGallery->setEventGallery(null);
            }
        }

        return $this;
    }
}

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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="sesionGallery", cascade={"persist"})
     */
    private $sesionGallery;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="showGallery", cascade={"persist"})
     */
    private $showGallery;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $slug;

    public function __construct()
    {
        $this->natureGallery = new ArrayCollection();
        $this->eventGallery = new ArrayCollection();
        $this->sesionGallery = new ArrayCollection();
        $this->showGallery = new ArrayCollection();
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
            if ($natureGallery->getNatureGallery() === $this) {
                $natureGallery->setNatureGallery(null);
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

    public function removeAlleventGallery(){
        $eventGallery = $this->getEventGallery();
        foreach ($eventGallery as $image){
            $this->removeEventGallery($image);
        }
    }

    /**
     * @return Collection|Image[]
     */
    public function getSesionGallery(): Collection
    {
        return $this->sesionGallery;
    }

    public function addSesionGallery(Image $sesionGallery): self
    {
        if (!$this->sesionGallery->contains($sesionGallery)) {
            $this->sesionGallery[] = $sesionGallery;
            $sesionGallery->setSesionGallery($this);
        }

        return $this;
    }

    public function removeSesionGallery(Image $sesionGallery): self
    {
        if ($this->sesionGallery->contains($sesionGallery)) {
            $this->sesionGallery->removeElement($sesionGallery);
            // set the owning side to null (unless already changed)
            if ($sesionGallery->getSesionGallery() === $this) {
                $sesionGallery->setSesionGallery(null);
            }
        }

        return $this;
    }

    public function removeAllSesionGallery(){
        $sesionGallery = $this->getSesionGallery();
        foreach ($sesionGallery as $image){
            $this->removeSesionGallery($image);
        }
    }

    /**
     * @return Collection|Image[]
     */
    public function getShowGallery(): Collection
    {
        return $this->showGallery;
    }

    public function addShowGallery(Image $showGallery): self
    {
        if (!$this->showGallery->contains($showGallery)) {
            $this->showGallery[] = $showGallery;
            $showGallery->setShowGallery($this);
        }

        return $this;
    }

    public function removeShowGallery(Image $showGallery): self
    {
        if ($this->showGallery->contains($showGallery)) {
            $this->showGallery->removeElement($showGallery);
            // set the owning side to null (unless already changed)
            if ($showGallery->getShowGallery() === $this) {
                $showGallery->setShowGallery(null);
            }
        }

        return $this;
    }

    public function removeAllShowGallery(){
        $showGallery = $this->getShowGallery();
        foreach ($showGallery as $image){
            $this->removeShowGallery($image);
        }
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
}

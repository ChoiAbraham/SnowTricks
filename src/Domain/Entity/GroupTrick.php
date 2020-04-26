<?php

namespace App\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Domain\Repository\GroupTrickRepository")
 */
class GroupTrick
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Entity\Trick", mappedBy="groupTrick")
     */
    private $trick;

    public function __construct($name = null)
    {
        $this->trick = new ArrayCollection();
        $this->name = $name;
    }

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

    /**
     * @return Collection|Trick[]
     */
    public function getTrick(): Collection
    {
        return $this->trick;
    }

    public function addTrick(Trick $trick): self
    {
        if (!$this->trick->contains($trick)) {
            $this->trick[] = $trick;
            $trick->setGroupTrick($this);
        }

        return $this;
    }

    public function removeTrick(Trick $trick): self
    {
        if ($this->trick->contains($trick)) {
            $this->trick->removeElement($trick);
            // set the owning side to null (unless already changed)
            if ($trick->getGroupTrick() === $this) {
                $trick->setGroupTrick(null);
            }
        }

        return $this;
    }
}

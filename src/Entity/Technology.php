<?php

namespace App\Entity;

use App\Repository\TechnologyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TechnologyRepository::class)
 */
class Technology
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="technology")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Expertise::class, mappedBy="technology")
     */
    private $expertise;

    public function __construct()
    {
        $this->expertise = new ArrayCollection();
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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Expertise[]
     */
    public function getExpertise(): Collection
    {
        return $this->expertise;
    }

    public function addExpertise(Expertise $expertise): self
    {
        if (!$this->expertise->contains($expertise)) {
            $this->expertise[] = $expertise;
            $expertise->setTechnology($this);
        }

        return $this;
    }

    public function removeExpertise(Expertise $expertise): self
    {
        if ($this->expertise->removeElement($expertise)) {
            // set the owning side to null (unless already changed)
            if ($expertise->getTechnology() === $this) {
                $expertise->setTechnology(null);
            }
        }

        return $this;
    }
}

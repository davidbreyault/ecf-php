<?php

namespace App\Entity;

use App\Repository\ExpertiseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExpertiseRepository::class)
 */
class Expertise
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $level;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $appreciated;

    /**
     * @ORM\ManyToOne(targetEntity=Technology::class, inversedBy="expertise")
     */
    private $technology;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="expertise")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getAppreciated(): ?int
    {
        return $this->appreciated;
    }

    public function setAppreciated(?int $appreciated): self
    {
        $this->appreciated = $appreciated;

        return $this;
    }

    public function getTechnology(): ?Technology
    {
        return $this->technology;
    }

    public function setTechnology(?Technology $technology): self
    {
        $this->technology = $technology;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}

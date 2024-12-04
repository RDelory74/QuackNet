<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $word= null;

    /**
     * @var Collection<int, Quack>
     */
    #[ORM\ManyToMany(targetEntity: Quack::class, mappedBy: 'tags')]
    private Collection $quacks;

    /**
     * @var Collection<int, Coincoin>
     */
    #[ORM\ManyToMany(targetEntity: Coincoin::class, mappedBy: 'Tag')]
    private Collection $coincoins;

    public function __construct()
    {
        $this->quacks = new ArrayCollection();
        $this->coincoins = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getWord(): ?string
    {
        return $this->word;
    }

    public function setWord(?string $word): void
    {
        $this->word = $word;
    }

    /**
     * @return Collection<int, Quack>
     */
    public function getQuacks(): Collection
    {
        return $this->quacks;
    }

    public function addQuack(Quack $quack): static
    {
        if (!$this->quacks->contains($quack)) {
            $this->quacks->add($quack);
            $quack->addTag($this);
        }

        return $this;
    }

    public function removeQuack(Quack $quack): static
    {
        if ($this->quacks->removeElement($quack)) {
            $quack->removeTag($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Coincoin>
     */
    public function getCoincoins(): Collection
    {
        return $this->coincoins;
    }

    public function addCoincoin(Coincoin $coincoin): static
    {
        if (!$this->coincoins->contains($coincoin)) {
            $this->coincoins->add($coincoin);
            $coincoin->addTag($this);
        }

        return $this;
    }

    public function removeCoincoin(Coincoin $coincoin): static
    {
        if ($this->coincoins->removeElement($coincoin)) {
            $coincoin->removeTag($this);
        }

        return $this;
    }




}
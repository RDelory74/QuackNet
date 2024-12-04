<?php

namespace App\Entity;

use App\Repository\CoincoinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoincoinRepository::class)]
class Coincoin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_time = null;

    #[ORM\ManyToOne(inversedBy: 'coincoins')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Duck $author = null;

    #[ORM\ManyToOne(inversedBy: 'parentId')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quack $parentId = null;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'coincoins')]
    private Collection $Tag;



    public function __construct(){
        $this->created_time = new \DateTimeImmutable();
        $this->Tag = new ArrayCollection();
}

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function setTags(Collection $tags): void
    {
        $this->tags = $tags;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }


    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getCreatedTime(): ?\DateTimeImmutable
    {
        return $this->created_time;
    }

    public function setCreatedTime(\DateTimeImmutable $created_time): static
    {
        $this->created_time = $created_time;

        return $this;
    }


    public function getAuthor(): ?Duck
    {
        return $this->author;
    }

    public function setAuthor(?Duck $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getParentId(): ?Quack
    {
        return $this->parentId;
    }

    public function setParentId(?Quack $parentId): static
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTag(): Collection
    {
        return $this->Tag;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->Tag->contains($tag)) {
            $this->Tag->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->Tag->removeElement($tag);

        return $this;
    }
}

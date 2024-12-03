<?php

namespace App\Entity;

use App\Repository\CoincoinRepository;
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



    #[ORM\Column(length: 255)]
    private ?string $picture = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_time = null;

    #[ORM\ManyToOne(inversedBy: 'coincoins')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Duck $author = null;

    #[ORM\ManyToOne(inversedBy: 'parentId')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quack $parentId = null;

    public function __construct(){
        $this->created_time = new \DateTimeImmutable();
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
}

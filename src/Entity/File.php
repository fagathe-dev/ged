<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FileRepository::class)]
class File
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPrivate = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(nullable: true)]
    private ?int $size = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'files')]
    private ?User $user = null;

    #[ORM\ManyToOne]
    private ?FileType $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isIsPrivate(): ?bool
    {
        return $this->isPrivate;
    }

    public function setIsPrivate(?bool $isPrivate): static
    {
        $this->isPrivate = $isPrivate;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getType(): ?FileType
    {
        return $this->type;
    }

    public function setType(?FileType $type): static
    {
        $this->type = $type;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\WeaponRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WeaponRepository::class)]
class Weapon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['api'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['api'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['api'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['api'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['api'])]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['api'])]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['api'])]
    private ?string $ammoStatus = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['api'])]
    private ?int $damage = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['api'])]
    private ?string $range = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['api'])]
    private ?int $weight = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['api'])]
    private ?User $author = null;

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getAmmoStatus(): ?string
    {
        return $this->ammoStatus;
    }

    public function setAmmoStatus(?string $ammoStatus): static
    {
        $this->ammoStatus = $ammoStatus;

        return $this;
    }

    public function getDamage(): ?int
    {
        return $this->damage;
    }

    public function setDamage(?int $damage): static
    {
        $this->damage = $damage;

        return $this;
    }

    public function getRange(): ?string
    {
        return $this->range;
    }

    public function setRange(?string $range): static
    {
        $this->range = $range;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(?int $weight): static
    {
        $this->weight = $weight;
        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;
        return $this;
    }
}

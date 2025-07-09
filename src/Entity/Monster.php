<?php

namespace App\Entity;

use App\Repository\MonsterRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MonsterRepository::class)]
class Monster
{
    #[Groups(['api'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $portrait = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $backgroundImage = null;

    #[Groups(['api'])]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $strengths = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $weaknesses = null;

    #[Groups(['api'])]
    #[ORM\Column]
    private ?int $lethality = null;

    #[Groups(['api'])]
    #[ORM\Column]
    private ?int $speed = null;

    #[Groups(['api'])]
    #[ORM\Column]
    private ?int $stealth = null;

    #[Groups(['api'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['api'])]
    #[ORM\Column(length: 255)]
    private ?string $rank = null;

    #[Groups(['api'])]
    #[ORM\Column]
    private ?int $dangerIndex = null;

    #[Groups(['api'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $backstory = null;

    #[Groups(['api'])]
    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $author = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPortrait(): ?string
    {
        return $this->portrait;
    }

    public function setPortrait(?string $portrait): static
    {
        $this->portrait = $portrait;

        return $this;
    }

    public function getBackgroundImage(): ?string
    {
        return $this->backgroundImage;
    }

    public function setBackgroundImage(?string $backgroundImage): static
    {
        $this->backgroundImage = $backgroundImage;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
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

    public function getStrengths(): ?string
    {
        return $this->strengths;
    }

    public function setStrengths(?string $strengths): static
    {
        $this->strengths = $strengths;

        return $this;
    }

    public function getWeaknesses(): ?string
    {
        return $this->weaknesses;
    }

    public function setWeaknesses(?string $weaknesses): static
    {
        $this->weaknesses = $weaknesses;

        return $this;
    }

    public function getLethality(): ?int
    {
        return $this->lethality;
    }

    public function setLethality(int $lethality): static
    {
        $this->lethality = $lethality;

        return $this;
    }

    public function getSpeed(): ?int
    {
        return $this->speed;
    }

    public function setSpeed(int $speed): static
    {
        $this->speed = $speed;

        return $this;
    }

    public function getStealth(): ?int
    {
        return $this->stealth;
    }

    public function setStealth(int $stealth): static
    {
        $this->stealth = $stealth;

        return $this;
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

    public function getRank(): ?string
    {
        return $this->rank;
    }

    public function setRank(string $rank): static
    {
        $this->rank = $rank;

        return $this;
    }

    public function getDangerIndex(): ?int
    {
        return $this->dangerIndex;
    }

    public function setDangerIndex(int $dangerIndex): static
    {
        $this->dangerIndex = $dangerIndex;

        return $this;
    }

    public function getBackstory(): ?string
    {
        return $this->backstory;
    }

    public function setBackstory(?string $backstory): static
    {
        $this->backstory = $backstory;

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

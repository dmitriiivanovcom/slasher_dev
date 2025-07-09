<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
class Character
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['api'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['api'])]
    private ?string $name = null;

    #[ORM\Column(type: 'integer')]
    #[Groups(['api'])]
    private ?int $strength = null;

    #[ORM\Column(type: 'integer')]
    #[Groups(['api'])]
    private ?int $agility = null;

    #[ORM\Column(type: 'integer')]
    #[Groups(['api'])]
    private ?int $intelligence = null;

    #[ORM\Column(type: 'integer')]
    #[Groups(['api'])]
    private ?int $charisma = null;

    #[ORM\Column(type: 'string', length: 300, nullable: true)]
    #[Groups(['api'])]
    private ?string $quote = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    #[Groups(['api'])]
    private ?string $role = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['api'])]
    private ?string $portrait = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['api'])]
    private ?string $backgroundImage = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['api'])]
    private ?string $motto = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['api'])]
    private ?string $weaknesses = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['api'])]
    private ?string $strengths = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['api'])]
    private ?string $description = null;

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

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getStrength(): ?int
    {
        return $this->strength;
    }

    public function setStrength(int $strength): self
    {
        $this->strength = $strength;
        return $this;
    }

    public function getAgility(): ?int
    {
        return $this->agility;
    }

    public function setAgility(int $agility): self
    {
        $this->agility = $agility;
        return $this;
    }

    public function getIntelligence(): ?int
    {
        return $this->intelligence;
    }

    public function setIntelligence(int $intelligence): self
    {
        $this->intelligence = $intelligence;
        return $this;
    }

    public function getCharisma(): ?int
    {
        return $this->charisma;
    }

    public function setCharisma(int $charisma): self
    {
        $this->charisma = $charisma;
        return $this;
    }

    public function getQuote(): ?string
    {
        return $this->quote;
    }

    public function setQuote(string $quote): self
    {
        $this->quote = $quote;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getPortrait(): ?string
    {
        return $this->portrait;
    }

    public function setPortrait(?string $portrait): self
    {
        $this->portrait = $portrait;
        return $this;
    }

    public function getBackgroundImage(): ?string
    {
        return $this->backgroundImage;
    }

    public function setBackgroundImage(?string $backgroundImage): self
    {
        $this->backgroundImage = $backgroundImage;
        return $this;
    }

    public function getMotto(): ?string
    {
        return $this->motto;
    }

    public function setMotto(?string $motto): self
    {
        $this->motto = $motto;
        return $this;
    }

    public function getWeaknesses(): ?string
    {
        return $this->weaknesses;
    }

    public function setWeaknesses(?string $weaknesses): self
    {
        $this->weaknesses = $weaknesses;
        return $this;
    }

    public function getStrengths(): ?string
    {
        return $this->strengths;
    }

    public function setStrengths(?string $strengths): self
    {
        $this->strengths = $strengths;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
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

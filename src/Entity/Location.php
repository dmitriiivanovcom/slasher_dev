<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $map = null;

    /**
     * @var Collection<int, SubLocations>
     */
    #[ORM\OneToMany(targetEntity: SubLocations::class, mappedBy: 'location', orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $subLocations;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $author = null;

    public function __construct()
    {
        $this->subLocations = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getMap(): ?string
    {
        return $this->map;
    }

    public function setMap(?string $map): static
    {
        $this->map = $map;

        return $this;
    }

    /**
     * @return Collection<int, SubLocations>
     */
    public function getSubLocations(): Collection
    {
        return $this->subLocations;
    }

    public function addSubLocation(SubLocations $subLocation): static
    {
        if (!$this->subLocations->contains($subLocation)) {
            $this->subLocations->add($subLocation);
            $subLocation->setLocation($this);
        }

        return $this;
    }

    public function removeSubLocation(SubLocations $subLocation): static
    {
        if ($this->subLocations->removeElement($subLocation)) {
            // set the owning side to null (unless already changed)
            if ($subLocation->getLocation() === $this) {
                $subLocation->setLocation(null);
            }
        }

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

<?php

namespace App\Entity;

use App\Repository\BookingTableRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[UniqueEntity('number', message: "Cette table existe déjà.")]
#[ORM\Entity(repositoryClass: BookingTableRepository::class)]
class BookingTable
{

    private const STATUS_IS_AVAILABLE = "disponible";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bookingTables')]
    private ?User $user = null;

    #[Assert\NotBlank(message: "Le nom de la table est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le nom de la table ne doit pas dépasser  {{ limit }} caractères.',
    )]
    #[Assert\Regex(
        pattern: '/^[0-9a-zA-Z]+$/',
        match: true,
        message: "Seul les chiffres, lettres de l'alphabet sont valides .",
    )]
    #[ORM\Column(length: 255)]
    private ?string $number = null;

    #[Assert\NotBlank(message: "L'emplacement de la table est obligatoire.")]
    #[ORM\Column(length: 255)]
    private ?string $location = null;
    #[Assert\Length(
        max: 255,
        maxMessage: "Le nom de l' emplacement de la table ne doit pas dépasser  {{ limit }} caractères.",
    )]

    #[Assert\NotBlank(message: "Le nombre maximum de personnes pour cette table est obligatoire.")]
    #[Assert\Range(
        min: 1,
        max: 15,
        notInRangeMessage: ' Au minimum {{ min }}cm et au maximum{{ max }}pour cette table.',
    )]
    #[ORM\Column]
    private ?int $peopleNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->status = self::STATUS_IS_AVAILABLE;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getPeopleNumber(): ?int
    {
        return $this->peopleNumber;
    }

    public function setPeopleNumber(?int $peopleNumber): static
    {
        $this->peopleNumber = $peopleNumber;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
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
}

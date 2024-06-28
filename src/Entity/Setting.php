<?php

namespace App\Entity;

use App\Repository\SettingRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SettingRepository::class)]
class Setting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[Assert\NotBlank(message: "L\' email est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "L\' email ne doit pas dépasser {{ limit }} caractères.",
    )]
    #[Assert\Email(
        message: "L\'email {{ value }} n\'est pas valide.",
    )]
    #[ORM\Column(length: 255)]
    private ?string $email = null;



    #[Assert\Length(
        max: 255,
        maxMessage: 'Le numéro de téléphone ne doit pas dépasser{{ limit }} caractères.',
    )]
    #[Assert\Regex(
        pattern: '/^[0-9\s\-\+\(\)]{7,20}+$/',
        match: true,
        message: 'Le numéro de téléphone est invalide.'
    )]
    #[ORM\Column(length: 255)]
    private ?string $phone = null;



    #[Assert\NotBlank(message: "L\' address est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "L\' address ne doit pas dépasser {{ limit }} caractères.",
    )]
    // #[Assert\Email(
    //     message: "L\'email {{ value }} n\'est pas valide.",
    // )]
    #[ORM\Column(length: 255)]
    private ?string $address = null;

    // #[Gedmo\Timestampable(on:'create')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    // #[Gedmo\Timestampable(on:'update')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $instagram = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $hours = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $facebook = null;
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): static
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getHours(): ?string
    {
        return $this->hours;
    }

    public function setHours(?string $hours): static
    {
        $this->hours = $hours;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): static
    {
        $this->facebook = $facebook;

        return $this;
    }



}

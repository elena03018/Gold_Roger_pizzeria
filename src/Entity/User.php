<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Cet email existe déjà')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    //Prénom
    #[Assert\NotBlank(message: "Le prénom est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Votre prénom ne doit pas dépasser {{ limit }} caractères.',
    )]
    #[Assert\Regex(
        pattern: "/^[0-9a-zA-Z-_' áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]+$/i",
        match: true,
        message: 'Seuls les chiffres, les lettres, l\' underscore et tiret sont autorisés.',
    )]
    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    //Nom
    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Votre nom ne doit pas dépasser {{ limit }} caractères.',
    )]
    #[Assert\Regex(
        pattern: "/^[0-9a-zA-Z-_' áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]+$/i",
        match: true,
        message: 'Seuls les chiffres, les lettres, l\' underscore et tiret sont autorisés.',
    )]
    #[ORM\Column(length: 255)]
    private ?string $lastName = null;


    //Email
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Length(
        max: 180,
        maxMessage: 'L\'email ne doit pas dépasser {{ limit }} caractères.',
    )]
    #[Assert\Email(
        message: 'L\' email {{ value }} est invalide.',
    )]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    //Phone
    #[Assert\NotBlank(message: "Le numéro de téléphone est obligatoire.")]
    #[Assert\Length(
        min: 6,
        max: 55,
        minMessage: 'Le numéro de téléphone doit contenir au minimum {{ limit }} caractères.',
        maxMessage: 'Le numéro de téléphone doit contenir au maximum {{ limit }} caractères.',
    )]
    #[Assert\Regex(
        pattern: '/^[0-9\s\-\+\(\)]{7,20}+$/',
        match: true,
        message: 'Le numéro de téléphone est invalide.'
    )]
    
    #[ORM\Column(length: 255, unique: true)]
    private ?string $phone = null;


    //Roles
    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    //Password
    /**
     * @var string The hashed password
     */
    #[Assert\NotBlank(message: "Le mot de passe est obligatoire.")]
    #[Assert\Length(
    min: 12,
    max: 255,
    minMessage: 'Le mot de passe doit contenir au minimum {{ limit }} caractères',
    maxMessage: 'Le mot de passe doit contenir au minimum {{ limit }} caractères',
    )]
    #[Assert\Regex(
        pattern: "/^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ỳ])(?=.*[0-9])(?=.*[^a-zà-ÿA-ZÀ-Ỳ0-9]).{11,255}$/",
        match: true,
        message: "Le mot de passe doit contentir au moins une lettre miniscule, majuscule, un chiffre et un caractère spécial.",
    )]
    #[Assert\NotCompromisedPassword(
        message: "Ce mot de passe est facilement piratable, veuillez en choisir un autre.",
    )]
    #[ORM\Column]
    private ?string $password = null;


    
    
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $verifiedAt = null;

    
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private bool $isVerified = false;

    /**
     * @var Collection<int, Contact>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Contact::class)]
    private Collection $contacts;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Post::class)]
    private Collection $posts;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Comment::class)]
    private Collection $comments;

    /**
     * @var Collection<int, BookingTime>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: BookingTime::class)]
    private Collection $bookingTimes;

    /**
     * @var Collection<int, BookingTable>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: BookingTable::class)]
    private Collection $bookingTables;

    /**
     * @var Collection<int, Booking>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Booking::class)]
    private Collection $bookings;

    public function __construct()
    {
        $this->roles[] = "ROLE_USER";
        $this->contacts = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->bookingTimes = new ArrayCollection();
        $this->bookingTables = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

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

    

    public function getVerifiedAt(): ?\DateTimeImmutable
    {
        return $this->verifiedAt;
    }

    public function setVerifiedAt(?\DateTimeImmutable $verifiedAt): static
    {
        $this->verifiedAt = $verifiedAt;

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


    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Contact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): static
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts->add($contact);
            $contact->setUser($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): static
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getUser() === $this) {
                $contact->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BookingTime>
     */
    public function getBookingTimes(): Collection
    {
        return $this->bookingTimes;
    }

    public function addBookingTime(BookingTime $bookingTime): static
    {
        if (!$this->bookingTimes->contains($bookingTime)) {
            $this->bookingTimes->add($bookingTime);
            $bookingTime->setUser($this);
        }

        return $this;
    }

    public function removeBookingTime(BookingTime $bookingTime): static
    {
        if ($this->bookingTimes->removeElement($bookingTime)) {
            // set the owning side to null (unless already changed)
            if ($bookingTime->getUser() === $this) {
                $bookingTime->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BookingTable>
     */
    public function getBookingTables(): Collection
    {
        return $this->bookingTables;
    }

    public function addBookingTable(BookingTable $bookingTable): static
    {
        if (!$this->bookingTables->contains($bookingTable)) {
            $this->bookingTables->add($bookingTable);
            $bookingTable->setUser($this);
        }

        return $this;
    }

    public function removeBookingTable(BookingTable $bookingTable): static
    {
        if ($this->bookingTables->removeElement($bookingTable)) {
            // set the owning side to null (unless already changed)
            if ($bookingTable->getUser() === $this) {
                $bookingTable->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): static
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setUser($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getUser() === $this) {
                $booking->setUser(null);
            }
        }

        return $this;
    }
}

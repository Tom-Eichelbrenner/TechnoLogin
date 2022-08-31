<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'Un utilisateur existe déjà avec ce nom d\'utilisateur.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 180,
        minMessage: 'Le nom d\'utilisateur doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Le nom d\'utilisateur doit faire au maximum {{ limit }} caractères.',
    )]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\Length(
        min: 6,
        max: 4096,
        minMessage: 'Le mot de passe doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Le mot de passe ne doit pas faire plus de {{ limit }} caractères.',
    )]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comment;

    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'likes')]
    private Collection $liked_articles;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $register_date = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(
        min: 1,
        max: 4096,
        minMessage: 'Le nom doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Le nom ne doit pas faire plus de {{ limit }} caractères.',
    )]
    private ?string $about = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Article::class)]
    private Collection $wrote_articles;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Media::class)]
    private Collection $media;


    public function __construct(?string $username = null)
    {
        $this->username = $username;
        $this->comment = new ArrayCollection();
        $this->liked_articles = new ArrayCollection();
        $this->register_date = new \DateTime('now');
        $this->wrote_articles = new ArrayCollection();
        $this->media = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comment->contains($comment)) {
            $this->comment->add($comment);
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getLikedArticles(): Collection
    {
        return $this->liked_articles;
    }

    public function addLikedArticle(Article $likedArticle): self
    {
        if (!$this->liked_articles->contains($likedArticle)) {
            $this->liked_articles->add($likedArticle);
            $likedArticle->addLike($this);
        }

        return $this;
    }

    public function removeLikedArticle(Article $likedArticle): self
    {
        if ($this->liked_articles->removeElement($likedArticle)) {
            $likedArticle->removeLike($this);
        }

        return $this;
    }

    public function getRegisterDate(): ?\DateTimeInterface
    {
        return $this->register_date;
    }

    public function setRegisterDate(\DateTimeInterface $register_date): self
    {
        $this->register_date = $register_date;

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(?string $about): self
    {
        $this->about = $about;

        return $this;
    }

    public function __toString(): string
    {
        return $this->username;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getWroteArticles(): Collection
    {
        return $this->wrote_articles;
    }

    public function addWroteArticle(Article $wroteArticle): self
    {
        if (!$this->wrote_articles->contains($wroteArticle)) {
            $this->wrote_articles->add($wroteArticle);
            $wroteArticle->setAuthor($this);
        }

        return $this;
    }

    public function removeWroteArticle(Article $wroteArticle): self
    {
        if ($this->wrote_articles->removeElement($wroteArticle)) {
            // set the owning side to null (unless already changed)
            if ($wroteArticle->getAuthor() === $this) {
                $wroteArticle->setAuthor(null);
            }
        }

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('username', new Assert\NotBlank());
        $classMetadata->addPropertyConstraint('password', new Assert\NotBlank());
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media->add($medium);
            $medium->setAuthor($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getAuthor() === $this) {
                $medium->setAuthor(null);
            }
        }

        return $this;
    }
}

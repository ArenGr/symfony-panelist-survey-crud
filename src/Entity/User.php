<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
	#[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(length: 255, type: Types::STRING)]
    private ?string $firstname = null;

	#[ORM\Column(length: 255, type: Types::STRING,  nullable: true)]
    private ?string $lastname = null;

	#[ORM\Column(length: 255, type: Types::STRING, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $country = null;

    #[ORM\Column(Types::BOOLEAN)]
    private ?bool $newsletter_agreement = false;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deleted_at;

    #[ORM\ManyToMany(targetEntity: Survey::class, inversedBy: 'users')]
	#[ORM\JoinTable("user_survey")]
    private Collection $surveys;


    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->deleted_at = new \DateTimeImmutable();
        $this->surveys = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function isNewsletterAgreement(): ?bool
    {
        return $this->newsletter_agreement;
    }

    public function setNewsletterAgreement(bool $newsletter_agreement): static
    {
        $this->newsletter_agreement = $newsletter_agreement;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?\DateTimeImmutable $deleted_at): static
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }

    /**
     * @return Collection<int, Survey>
     */
    public function getSurvey(): Collection
    {
        return $this->surveys;
    }

    public function addSurvey(Survey $survey): static
    {
        if (!$this->surveys->contains($survey)) {
            $this->surveys->add($survey);
        }

        return $this;
    }

    public function removeSurvey(Survey $survey): static
    {
        $this->surveys->removeElement($survey);

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\SchoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SchoolRepository::class)]
#[ORM\HasLifecycleCallbacks]
class School
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Naziv je obavezno polje')]
    #[Assert\Length(max: 255, maxMessage: 'Naziv ne može biti duži od {{ limit }} karaktera')]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Assert\NotBlank(message: 'Tip škole je obavezno polje')]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?SchoolType $type = null;

    #[Assert\NotBlank(message: 'Grad je obavezno polje')]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $city = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @var Collection<int, UserDelegateSchool>
     */
    #[ORM\OneToMany(targetEntity: UserDelegateSchool::class, mappedBy: 'school')]
    private Collection $userDelegateSchools;

    public function __construct()
    {
        $this->userDelegateSchools = new ArrayCollection();
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

    public function getType(): ?SchoolType
    {
        return $this->type;
    }

    public function setType(?SchoolType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): static
    {
        $this->createdAt = new \DateTime();

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): static
    {
        $this->updatedAt = new \DateTime();

        return $this;
    }

    /**
     * @return Collection<int, UserDelegateSchool>
     */
    public function getUserDelegateSchools(): Collection
    {
        return $this->userDelegateSchools;
    }

    public function addUserDelegateSchool(UserDelegateSchool $userDelegateSchool): static
    {
        if (!$this->userDelegateSchools->contains($userDelegateSchool)) {
            $this->userDelegateSchools->add($userDelegateSchool);
            $userDelegateSchool->setSchool($this);
        }

        return $this;
    }

    public function removeUserDelegateSchool(UserDelegateSchool $userDelegateSchool): static
    {
        if ($this->userDelegateSchools->removeElement($userDelegateSchool)) {
            // set the owning side to null (unless already changed)
            if ($userDelegateSchool->getSchool() === $this) {
                $userDelegateSchool->setSchool(null);
            }
        }

        return $this;
    }
}

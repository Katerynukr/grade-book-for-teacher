<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 */
class Student
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message="Field can not be empty!")
     * @Assert\Length(
     *      min = 2,
     *      max = 64,
     *      minMessage = "The name is too short. Minimum length is {{ limit }} characters",
     *      maxMessage = "The name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Type(
     *     type="alpha",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message="Field can not be empty!")
     * @Assert\Length(
     *      min = 2,
     *      max = 64,
     *      minMessage = "The surname is too short. Minimum length is {{ limit }} characters",
     *      maxMessage = "The surname cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Type(
     *     type="alpha",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=64)
     *   @Assert\NotBlank(message="Field can not be empty!")
     * @Assert\Length(
     *      min = 5,
     *      max = 64,
     *      minMessage = "The email is too short. Minimum length is {{ limit }} characters",
     *      maxMessage = "The email cannot be longer than {{ limit }} characters"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=32)
     *   @Assert\NotBlank(message="Field can not be empty!")
     * @Assert\Length(
     *      min = 5,
     *      max = 64,
     *      minMessage = "The phone number is too short. Minimum length is {{ limit }} characters",
     *      maxMessage = "The phone number cannot be longer than {{ limit }} characters"
     * )
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity=Grade::class, mappedBy="student")
     */
    private $grades;

    public function __construct()
    {
        $this->grades = new ArrayCollection();
    }

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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection|Grade[]
     */
    public function getGrades(): Collection
    {
        return $this->grades;
    }

    public function addGrade(Grade $grade): self
    {
        if (!$this->grades->contains($grade)) {
            $this->grades[] = $grade;
            $grade->setStudent($this);
        }

        return $this;
    }

    public function removeGrade(Grade $grade): self
    {
        if ($this->grades->removeElement($grade)) {
            // set the owning side to null (unless already changed)
            if ($grade->getStudent() === $this) {
                $grade->setStudent(null);
            }
        }

        return $this;
    }
}

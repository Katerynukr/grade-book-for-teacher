<?php

namespace App\Entity;

use App\Repository\LecturRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LecturRepository::class)
 */
class Lectur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     *  @Assert\NotBlank(message="Field can not be empty!")
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
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Description field can not be empty!")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Grade::class, mappedBy="lectur")
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
            $grade->setLectur($this);
        }

        return $this;
    }

    public function removeGrade(Grade $grade): self
    {
        if ($this->grades->removeElement($grade)) {
            // set the owning side to null (unless already changed)
            if ($grade->getLectur() === $this) {
                $grade->setLectur(null);
            }
        }

        return $this;
    }
}

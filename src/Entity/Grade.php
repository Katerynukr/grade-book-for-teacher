<?php

namespace App\Entity;

use App\Repository\GradeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=GradeRepository::class)
 */
class Grade
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     *  @Assert\Positive
     */
    private $lectur_id;

    /**
     * @ORM\ManyToOne(targetEntity=Lectur::class, inversedBy="grades")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lectur;

    /**
     * @ORM\Column(type="integer")
     *  @Assert\Positive
     */
    private $student_id;

    /**
     * @ORM\ManyToOne(targetEntity=Student::class, inversedBy="grades")
     * @ORM\JoinColumn(nullable=false)
     */
    private $student;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive(message="Grade field cannot have zero or negative amount of pages")
     */
    private $grade;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLecturId(): ?int
    {
        return $this->lectur_id;
    }

    public function setLecturId(int $lectur_id): self
    {
        $this->lectur_id = $lectur_id;

        return $this;
    }

    public function getLectur(): ?Lectur
    {
        return $this->lectur;
    }

    public function setLectur(?Lectur $lectur): self
    {
        $this->lectur = $lectur;

        return $this;
    }

    public function getStudentId(): ?int
    {
        return $this->student_id;
    }

    public function setStudentId(int $student_id): self
    {
        $this->student_id = $student_id;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function getGrade(): ?int
    {
        return $this->grade;
    }

    public function setGrade(int $grade): self
    {
        $this->grade = $grade;

        return $this;
    }
    
}

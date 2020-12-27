<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=NoteRepository::class)
 */
class Note
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank( message="Les valeurs ne peuvent pas etre vide !")
     * @Assert\Length(
     *            min = 3,
     *            max = 15,
     *            minMessage = "l'intitulet de la matier ne peut pas contenir moin de {{ limit }} caracteres !",
     *            maxMessage = "l'intitulet de la matier ne peut pas contenir plus de {{ limit }} caracteres !"
     *)
     */
    private $matiere;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank( message="Les valeurs ne peuvent pas etre vide !")
     * @Assert\GreaterThan(
     *     value = 0,
     *     message= "La note ne peut pas etre inferieur ou egale a 0 points"
     * )
     * @Assert\LessThan(
     *     value = 20,
     *     message= "La note ne peut pas etre superieur a 20 points"
     * )
     */
    private $valeur;

    /**
     * @ORM\ManyToOne(targetEntity=Student::class, inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $student;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatiere(): ?string
    {
        return $this->matiere;
    }

    public function setMatiere(string $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }

    public function getValeur(): ?float
    {
        return $this->valeur;
    }

    public function setValeur(float $valeur): self
    {
        $this->valeur = $valeur;

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
}

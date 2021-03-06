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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank( message="Les valeurs ne peuvent pas etre vide !")
     * @Assert\Length(
     *            min = 3,
     *            max = 15,
     *            minMessage = "le nom ne peut pas contenir moin de {{ limit }} caracteres !",
     *            maxMessage = "le nom ne peut pas contenir plus de {{ limit }} caracteres !"
     *)
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Votre nom ne peut contenir de chiffres !"
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank( message="Les valeurs ne peuvent pas etre vide !")
     * @Assert\Length(
     *            min = 3,
     *            max = 15,
     *            minMessage = "le prenom ne peut pas contenir moin de {{ limit }} caracteres !",
     *            maxMessage = "le prenom ne peut pas contenir plus de {{ limit }} caracteres !"
     *)
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Votre prenom ne peut contenir de chiffres !"
     * )
     */
    private $prenom;

    /**
     * @ORM\Column(type="date")
     */
    private $dateAnnive;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="student", orphanRemoval=true)
     */
    private $notes;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateAnnive(): ?\DateTimeInterface
    {
        return $this->dateAnnive;
    }

    public function setDateAnnive(\DateTimeInterface $dateAnnive): self
    {
        $this->dateAnnive = $dateAnnive;

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setStudent($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getStudent() === $this) {
                $note->setStudent(null);
            }
        }

        return $this;
    }

    //function toArray pour retourner un tableau pour la response JsonResponse
    public function toArray(){
      return [
        "id" => $this->getId(),
        "Nom" => $this->getNom(),
        "Prenom" => $this->getPrenom(),
        "date_annive" => $this->getDateAnnive()->format("d-m-Y")
      ];
    }
}

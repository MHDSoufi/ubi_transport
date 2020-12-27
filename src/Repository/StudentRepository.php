<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Note;
/**
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
  private $manager;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Student::class);
        $this->manager = $manager;
    }

    public function addStudent(Student $student):Student{
        $this->manager->persist($student);
        $this->manager->flush();
        return $student;
    }

    public function updateStudent(Student $student):Student{
      $this->manager->persist($student);
      $this->manager->flush();
      return $student;
    }
    // /**
    //  * @return Student[] Returns an array of Student objects
    //  */
    public function deleteStudent(Student $student){
      $this->manager->remove($student);
      $this->manager->flush();
    }

    public function addNoteStudent(Note $note){
      $this->manager->persist($note);
      $this->manager->flush();
    }
}

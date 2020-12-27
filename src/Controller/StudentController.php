<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Repository\StudentRepository;
use App\Entity\Student;

/**
 * class StudentController
 * @Route(path="/student")
 */
class StudentController extends AbstractController
{
    private $studentRepository;

    public function __construct(StudentRepository $studentRepository){
        $this->studentRepository = $studentRepository;
    }

    /**
    * @Route("/add", name="add_student", methods={"POST"})
    *
    */
    public function add(Request $request): JsonResponse{
        $data = json_decode($request->getContent(), true);
        $newStudent = new Student();
        $newStudent->setNom($data['nom'])
                ->setPrenom($data['prenom'])
                ->setDateAnnive(new \DateTime($data['dateNaissance']));
        $addedStudent = $this->studentRepository->addStudent($newStudent);
        return new JsonResponse($addedStudent->toArray(), Response::HTTP_OK);
    }

    /**
    * @Route("/update/{id}", name="update_student", methods={"PUT"})
    *
    */
    public function update($id, Request $request): JsonResponse{
      $student = $this->studentRepository->findOneBy(['id'=>$id]);
      $data = json_decode($request->getContent(), true);
      if (isset($student)) {
            empty($data['nom']) ? true : $student->setNom($data['nom']);
            empty($data['prenom']) ? true : $student->setPrenom($data['prenom']);
            empty($data['dateNaissance']) ? true : $student->setDateAnnive(new \DateTime($data['dateNaissance']));

            $updatedStudent = $this->studentRepository->updateStudent($student);
            return new JsonResponse($updatedStudent->toArray(), Response::HTTP_OK);
      }else{
            return new JsonResponse(['error'=> "Cette etudiant n'existe pas !"], Response::HTTP_NOT_FOUND);
      }
    }

    /**
    * @Route("/delete/{id}", name="delete_student", methods={"DELETE"})
    *
    */
    public function delete($id): JsonResponse{
      $student = $this->studentRepository->findOneBy(['id'=>$id]);
      if (isset($student)) {
        $this->studentRepository->deleteStudent($student);
         return new JsonResponse(['status'=> "Cette etudiant a bien ete supprimer !"], Response::HTTP_NO_CONTENT);
      }else {
        return new JsonResponse(['error'=> "Cette etudiant n'existe pas !"], Response::HTTP_NOT_FOUND);
      }
    }
}

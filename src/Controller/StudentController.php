<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Repository\StudentRepository;
use App\Entity\Student;
use App\Entity\Note;

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
    public function add(Request $request, ValidatorInterface $validator): JsonResponse{
        $data = json_decode($request->getContent(), true);
        $newStudent = new Student();
        $newStudent->setNom($data['nom'])
                ->setPrenom($data['prenom'])
                ->setDateAnnive(new \DateTime($data['dateNaissance']));
        $errors = $validator->validate($newStudent);
        if (count($errors) > 0) {
          $errorMessage = $errors[0]->getMessage();
          return new JsonResponse(["error" => $errorMessage], Response::HTTP_OK);
        }else {
          $addedStudent = $this->studentRepository->addStudent($newStudent);
          return new JsonResponse($addedStudent->toArray(), Response::HTTP_OK);
        }
    }

    /**
    * @Route("/update/{id}", name="update_student", methods={"PUT"})
    *
    */
    public function update($id, Request $request, ValidatorInterface $validator): JsonResponse{
      $student = $this->studentRepository->findOneBy(['id'=>$id]);
      $data = json_decode($request->getContent(), true);
      if (isset($student)) {
            empty($data['nom']) ? true : $student->setNom($data['nom']);
            empty($data['prenom']) ? true : $student->setPrenom($data['prenom']);
            empty($data['dateNaissance']) ? true : $student->setDateAnnive(new \DateTime($data['dateNaissance']));
            $errors = $validator->validate($student);
            if (count($errors) > 0) {
              $errorMessage = $errors[0]->getMessage();
              return new JsonResponse(["error" => $errorMessage], Response::HTTP_OK);
            }else {
              $updatedStudent = $this->studentRepository->updateStudent($student);
              return new JsonResponse($updatedStudent->toArray(), Response::HTTP_OK);
            }
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

    /**
    * @Route("/note/{id}", name="affect_note", methods={"POST"})
    */
    public function addNote($id, Request $request, ValidatorInterface $validator): JsonResponse{
      $student = $this->studentRepository->findOneBy(["id"=>$id]);
      $data = json_decode($request->getContent(), true);
      if (isset($student)) {
        $note = new Note();
        $note->setMatiere($data['matiere'])
             ->setValeur((float) $data["valeur"])
             ->setStudent($student);
        $errors = $validator->validate($note);
        if (count($errors) > 0) {
          $errorMessage = $errors[0]->getMessage();
          return new JsonResponse(["error" => $errorMessage], Response::HTTP_OK);
        }else{
          $this->studentRepository->addNoteStudent($note);
          return new JsonResponse(['status'=> "La note a ete affecter !"], Response::HTTP_OK);
        }
      }else{
        return new JsonResponse(['error'=> "Cette etudiant n'existe pas !"], Response::HTTP_NOT_FOUND);
      }
    }

    /**
    * @Route("/moyene/{id}", name="moyene_student", methods={"GET"})
    */
    public function moyen($id): JsonResponse{
      $student = $this->studentRepository->findOneBy(["id"=>$id]);
      $notes = $student->getNotes();
      $moyene = $this->calculeMoyene($notes);
      return  new JsonResponse(['moyene'=>"la moyene de l'etudiant ". $student->getNom() . ' '. $student->getPrenom().' ' .$moyene], Response::HTTP_OK);
    }

    //function de calcule de moyene
    public function calculeMoyene($notes): float{
      $somme = 0;
      foreach ($notes as $note) {
        $somme += $note->getValeur();
      }
      return $somme/count($notes);
    }
}

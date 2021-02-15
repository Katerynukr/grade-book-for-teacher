<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Grade;
use App\Entity\Lectur;
use App\Entity\Student;

class StudentController extends AbstractController
{
    /**
     * @Route("/student", name="student_index", methods= {"GET"} )
     */
    public function index(Request $r): Response
    {

         $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

         $students = $this->getDoctrine()
         ->getRepository(Student::class);
         
         if($r->query->get('sort_by') == 'sort_by_name_asc'){
             $students = $students->findBy([],['name' => 'asc']);
         }elseif($r->query->get('sort_by') == 'sort_by_name_desc'){
             $students = $students->findBy([],['name' => 'desc']);
         }elseif($r->query->get('sort_by') == 'sort_by_surname_asc'){
             $students = $students->findBy([],['surname' => 'asc']);
         }elseif($r->query->get('sort_by') == 'sort_by_surname_desc'){
             $students = $students->findBy([],['surname' => 'desc']);
         }else{
             $students = $students->findAll();
         }
         
         return $this->render('student/index.html.twig', [
             'errors' => $r->getSession()->getFlashBag()->get('errors', []),
             'students' => $students,
             'sortBy' => $r->query->get('sort_by') ?? 'default',
             'success' => $r->getSession()->getFlashBag()->get('success', []),
         ]);
    }
     /**
       * @Route("/student/info/{id}", name="student_info", methods= {"GET"})
       */
      public function infoStudent(Request $r, int $id): Response
      {
          $student = $this->getDoctrine()
          ->getRepository(Student::class)
          ->find($id);
          $repository = $this->getDoctrine()->getRepository(Grade::class);
          $grades = $repository->findBy(array('student_id' => $id));
        
        $results = [];
        foreach($grades as $i => $grade){
            $results[$i]['grade'] = $grade->getGrade(); 
            $results[$i]['lectur'] = $grade->getLectur()->getName();
        }
       

          return $this->render('student/info.html.twig', [
            
           'student' => $student,
           'results' => $results,
           
          ]);
      }

    /**
      * @Route("/student/create", name="student_create", methods= {"GET"})
      */
      public function create(Request $r): Response
      {
         //  $mechanick_name = $r->getSession()->getFlashBag()->get('mechanick_name', []);
         //  $mechanick_surname = $r->getSession()->getFlashBag()->get('mechanick_surname', []);
  
          return $this->render('student/create.html.twig', [
              'errors' => $r->getSession()->getFlashBag()->get('errors', []),
              //'mechanick_name' => $mechanick_name[0] ?? '',
              //'mechanick_surname' => $mechanick_surname[0] ?? '',
              'success' => $r->getSession()->getFlashBag()->get('success', [])
          ]);
      }
  
       /**
       * @Route("/student/store", name="student_store", methods= {"POST"})
       */
      public function store(Request $r, ValidatorInterface $validator): Response
      {
          $submittedToken = $r->request->get('token');
  
          if (!$this->isCsrfTokenValid('check_csrf_hidden', $submittedToken)) {
              $r->getSession()->getFlashBag()->add('errors', 'Bad talken CSRF');
              return $this->redirectToRoute('student_create');
          }
  
          $student = new Student;
          $student->
          setName($r->request->get('student_name'))->
          setSurname($r->request->get('student_surname'))->
          setEmail($r->request->get('student_email'))->
          setPhone($r->request->get('student_phone'));
  
          $errors = $validator->validate($student);
  
          // dd(count($errors));
          if (count($errors) > 0){
              foreach($errors as $error) {
                  $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
              }
              $r->getSession()->getFlashBag()->add('student_name', $r->request->get('student_name'));
              $r->getSession()->getFlashBag()->add('student_surname', $r->request->get('student_surname'));
              $r->getSession()->getFlashBag()->add('student_email', $r->request->get('student_email'));
              $r->getSession()->getFlashBag()->add('student_phone', $r->request->get('student_phone'));
              return $this->redirectToRoute('student_create');
          }
  
          //creating entity manager sending data to database
          $entityManager = $this->getDoctrine()->getManager();
          //organizing data to be send
          $entityManager->persist($student);
          //wrting
          $entityManager->flush();
  
          $r->getSession()->getFlashBag()->add('success', 'Mechanic was successfully created');
  
          return $this->redirectToRoute('student_index');
      }
  
      /**
       * @Route("/student/edit/{id}", name="student_edit", methods= {"GET"})
       */
      public function edit(Request $r, int $id): Response
      {
          $student = $this->getDoctrine()
          ->getRepository(Student::class)
          ->find($id);
          
          return $this->render('student/edit.html.twig', [
              'student' => $student,
              'errors' => $r->getSession()->getFlashBag()->get('errors', []),
              'success' => $r->getSession()->getFlashBag()->get('success', [])
          ]);
      }
  
       /**
       * @Route("/student/update/{id}", name="student_update", methods= {"POST"})
       */
      public function update(Request $r, int $id, ValidatorInterface $validator): Response
      {
          $submittedToken = $r->request->get('token');
  
          $student = $this->getDoctrine()
          ->getRepository(Student::class)
          ->find($id);
  
          if (!$this->isCsrfTokenValid('check_csrf_hidden', $submittedToken)) {
              $r->getSession()->getFlashBag()->add('errors', 'Bad talken CSRF');
              return $this->redirectToRoute('student_edit', ['id'=>$mechanick->getId()]);
          }
  
          $student->
          setName($r->request->get('student_name'))->
          setSurname($r->request->get('student_surname'))->
          setEmail($r->request->get('student_email'))->
          setPhone($r->request->get('student_phone'));
  
          $errors = $validator->validate($student);
  
          // dd(count($errors));
          if (count($errors) > 0){
              foreach($errors as $error) {
                  $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
              }
              $r->getSession()->getFlashBag()->add('student_name', $r->request->get('student_name'));
              $r->getSession()->getFlashBag()->add('student_surname', $r->request->get('student_surname'));
              $r->getSession()->getFlashBag()->add('student_email', $r->request->get('student_email'));
              $r->getSession()->getFlashBag()->add('student_phone', $r->request->get('student_phone'));
              return $this->redirectToRoute('student_edit', ['id'=>$student->getId()]);
          }
  
          //creating entity manager sending data to database
          $entityManager = $this->getDoctrine()->getManager();
          //organizing data to be send
          $entityManager->persist($student);
          //wrting
          $entityManager->flush();
  
          $r->getSession()->getFlashBag()->add('success', 'Mechanic was successfully edited');
  
          return $this->redirectToRoute('student_index');
      }
  
      /**
       * @Route("/student/delete/{id}", name="student_delete", methods= {"POST"})
       */
      public function delete(Request $r, int $id): Response
      {
          $submittedToken = $r->request->get('token');
  
          if (!$this->isCsrfTokenValid('check_csrf_hidden', $submittedToken)) {
              $r->getSession()->getFlashBag()->add('errors', 'Bad talken CSRF');
              return $this->redirectToRoute('student_index');
          }
  
          $student = $this->getDoctrine()
          ->getRepository(Student::class)
          ->find($id);
  
          if ($student->getGrades()->count() > 0) {
              $r->getSession()->getFlashBag()->add('errors', 'You cannot deleate the student because it has grades' );
              return $this->redirectToRoute('student_index');
          }
  
          //creating entity manager sending data to database
          $entityManager = $this->getDoctrine()->getManager();
          //organizing data to be send
          $entityManager->remove($student);
          //wrting
          $entityManager->flush();
  
          $r->getSession()->getFlashBag()->add('success', 'Student was successfully deleted');
  
          return $this->redirectToRoute('student_index');
      }
}

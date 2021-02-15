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


class GradeController extends AbstractController
{
    /**
     * @Route("/grade", name="grade_index", methods={"GET"})
     */
    public function index(Request $r): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $grades =  $this->getDoctrine()
        ->getRepository(Grade::class);

        if($r->query->get('sort_by') == 'lowest_grade'){
            $grades = $grades->findBy([],['grade' => 'asc']);
        }elseif($r->query->get('sort_by') == 'highest_grade'){
            $grades = $grades->findBy([],['grade' => 'desc']);
        }else{
            $grades = $grades->findAll();
        }

        return $this->render('grade/index.html.twig', [
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'grades'=>$grades,
            'sortBy' => $r->query->get('sort_by') ?? 'default',
            'success' => $r->getSession()->getFlashBag()->get('success', [])
        ]);
    }

    /**
     * @Route("/grade/create", name="grade_create", methods={"GET"})
     */
    public function create(Request $r): Response
    {
        
        $lectures =  $this->getDoctrine()
        ->getRepository(Lectur::class)
        ->findAll();
        
        $students = $this->getDoctrine()
        ->getRepository(Student::class)
        ->findAll();
        
        
        return $this->render('grade/create.html.twig', [
            'lectures' => $lectures,
            'students'=>$students,
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'success' => $r->getSession()->getFlashBag()->get('success', []),
        ]);
    }

     /**
     * @Route("/grade/create", name="grade_store", methods={"POST"})
     */
    public function store(Request $r, ValidatorInterface $validator): Response
    {
        $submittedToken = $r->request->get('token');

        if (!$this->isCsrfTokenValid('check_csrf_hidden', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Bad talken CSRF');
            return $this->redirectToRoute('grade_create');
        }

        $lectur =  $this->getDoctrine()
        ->getRepository(Lectur::class)
        ->find($r->request->get('grade_lectur_id'));

        if($lectur == null){
            $r->getSession()->getFlashBag()->add('errors', 'Choose lectur from the list');
        }

        $student =  $this->getDoctrine()
        ->getRepository(Student::class)
        ->find($r->request->get('grade_student_id'));

        if($student == null){
            $r->getSession()->getFlashBag()->add('errors', 'Choose student from the list');
        }

        $grade = new Grade;

        $grade->
        setLectur($lectur)->
        setStudent($student)->
        setGrade((int)$r->request->get('grade_grade'));

        $errors = $validator->validate($grade);
        if (count($errors) > 0){
            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
            return $this->redirectToRoute('grade_create');
        }
        if(null === $student) {
            return $this->redirectToRoute('grade_create');
        }
        if(null === $lectur) {
            return $this->redirectToRoute('grade_create');
        }


        //creating entity manager sending data to database
        $entityManager = $this->getDoctrine()->getManager();
        //organizing data to be send
        $entityManager->persist($grade);
        //wrting
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'Grade was successfully created');

        return $this->redirectToRoute('grade_index');
    }

    /**
     * @Route("/grade/edit/{id}", name="grade_edit", methods= {"GET"})
     */
    public function edit(Request $r, int $id): Response
    {
        $lectures =  $this->getDoctrine()
        ->getRepository(Lectur::class)
        ->findAll();
        
        $students = $this->getDoctrine()
        ->getRepository(Student::class)
        ->findAll();

        $grade =  $this->getDoctrine()
        ->getRepository(Grade::class)
        ->find($id);

        
        return $this->render('grade/edit.html.twig', [
            'lectures' => $lectures,
            'students'=>$students,
            'grade'=>$grade,
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'success' => $r->getSession()->getFlashBag()->get('success', []),
        ]);
    }

     /**
     * @Route("/grade/update/{id}", name="grade_update", methods= {"POST"})
     */
    public function update(Request $r, int $id, ValidatorInterface $validator): Response
    {
        $submittedToken = $r->request->get('token');


        $grade =  $this->getDoctrine()
        ->getRepository(Grade::class)
        ->find($id);

        if (!$this->isCsrfTokenValid('check_csrf_hidden', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Bad talken CSRF');
            return $this->redirectToRoute('grade_edit' , ['id'=>$grade->getId()]);
        }
        
        $lectur =  $this->getDoctrine()
        ->getRepository(Lectur::class)
        ->find($r->request->get('grade_lectur_id'));

        if($lectur == null){
            $r->getSession()->getFlashBag()->add('errors', 'Choose lectur from the list');
        }
        
        $student = $this->getDoctrine()
        ->getRepository(Student::class)
        ->find($r->request->get('grade_student_id'));

        if($student == null){
            $r->getSession()->getFlashBag()->add('errors', 'Choose student from the list');
        }

        $grade->
        setLectur($lectur)->
        setStudent($student)->
        setGrade((int)$r->request->get('grade_grade'));

        $errors = $validator->validate($grade);
        if (count($errors) > 0){
            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
            return $this->redirectToRoute('grade_edit', ['id'=>$grade->getId()] );
        }
        if(null === $student) {
            return $this->redirectToRoute('grade_edit', ['id'=>$grade->getId()]);
        }
        if(null === $lectur) {
            return $this->redirectToRoute('grade_edit', ['id'=>$grade->getId()]);
        }

        //creating entity manager sending data to database
        $entityManager = $this->getDoctrine()->getManager();
        //organizing data to be send
        $entityManager->persist($grade);
        //wrting
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'Truck was successfully edited');

        return $this->redirectToRoute('grade_index');
    }

    /**
     * @Route("/grade/delete/{id}", name="grade_delete", methods= {"POST"})
     */
    public function delete(Request $r, int $id): Response
    {
        $submittedToken = $r->request->get('token');

        if (!$this->isCsrfTokenValid('check_csrf_hidden', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Bad talken CSRF');
            return $this->redirectToRoute('grade_index');
        }

        $grade =  $this->getDoctrine()
        ->getRepository(Grade::class)
        ->find($id);

        //creating entity manager sending data to database
        $entityManager = $this->getDoctrine()->getManager();
        //organizing data to be send
        $entityManager->remove($grade);
        //wrting
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'Grade was successfully deleted');

        return $this->redirectToRoute('grade_index');
    }
}

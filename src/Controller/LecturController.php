<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Lectur;

class LecturController extends AbstractController
{
    /**
     * @Route("/lectur", name="lectur_index",  methods= {"GET"})
     */
    public function index(Request $r): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $lectures = $this->getDoctrine()
        ->getRepository(Lectur::class);
        
        if($r->query->get('sort_by') == 'sort_by_name_asc'){
            $lectures = $lectures->findBy([],['name' => 'asc']);
        }elseif($r->query->get('sort_by') == 'sort_by_name_desc'){
            $lectures = $lectures->findBy([],['name' => 'desc']);
        }else{
            $lectures = $lectures->findAll();
        }
        
        return $this->render('lectur/index.html.twig', [
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'lectures' => $lectures,
            'sortBy' => $r->query->get('sort_by') ?? 'default',
            'success' => $r->getSession()->getFlashBag()->get('success', [])
        ]);
    }
 
      /**
      * @Route("/lectur/create", name="lectur_create", methods= {"GET"})
      */
     public function create(Request $r): Response
     {
        //$mechanick_name = $r->getSession()->getFlashBag()->get('mechanick_name', []);
        //$mechanick_surname = $r->getSession()->getFlashBag()->get('mechanick_surname', []);
 
         return $this->render('lectur/create.html.twig', [
             'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            // 'mechanick_name' => $mechanick_name[0] ?? '',
            //'mechanick_surname' => $mechanick_surname[0] ?? '',
             'success' => $r->getSession()->getFlashBag()->get('success', [])
         ]);
     }
 
      /**
      * @Route("/lectur/store", name="lectur_store", methods= {"POST"})
      */
     public function store(Request $r, ValidatorInterface $validator): Response
     {
         $submittedToken = $r->request->get('token');
 
         if (!$this->isCsrfTokenValid('check_csrf_hidden', $submittedToken)) {
             $r->getSession()->getFlashBag()->add('errors', 'Bad talken CSRF');
             return $this->redirectToRoute('lectur_create');
         }
 
         $lectur = new Lectur;
         $lectur->
         setName($r->request->get('lectur_name'))->
         setDescription($r->request->get('lectur_decription'));
 
         $errors = $validator->validate($lectur);
 
         // dd(count($errors));
         if (count($errors) > 0){
             foreach($errors as $error) {
                 $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
             }
             $r->getSession()->getFlashBag()->add('lectur_name', $r->request->get('lectur_name'));
             $r->getSession()->getFlashBag()->add('lectur_description', $r->request->get('lectur_description'));
             return $this->redirectToRoute('lectur_create');
         }
 
         //creating entity manager sending data to database
         $entityManager = $this->getDoctrine()->getManager();
         //organizing data to be send
         $entityManager->persist($lectur);
         //wrting
         $entityManager->flush();
 
         $r->getSession()->getFlashBag()->add('success', 'Lectur was successfully created');
 
         return $this->redirectToRoute('lectur_index');
     }
 
     /**
      * @Route("/lectur/edit/{id}", name="lectur_edit", methods= {"GET"})
      */
     public function edit(Request $r, int $id): Response
     {
         $lectur = $this->getDoctrine()
         ->getRepository(Lectur::class)
         ->find($id);
         
         return $this->render('lectur/edit.html.twig', [
             'lectur' => $lectur,
             'errors' => $r->getSession()->getFlashBag()->get('errors', []),
             'success' => $r->getSession()->getFlashBag()->get('success', [])
         ]);
     }
 
      /**
      * @Route("/lectur/update/{id}", name="lectur_update", methods= {"POST"})
      */
     public function update(Request $r, int $id, ValidatorInterface $validator): Response
     {
         $submittedToken = $r->request->get('token');
 
         $lectur = $this->getDoctrine()
         ->getRepository(Lectur::class)
         ->find($id);
 
         if (!$this->isCsrfTokenValid('check_csrf_hidden', $submittedToken)) {
             $r->getSession()->getFlashBag()->add('errors', 'Bad talken CSRF');
             return $this->redirectToRoute('lectur_edit', ['id'=>$lectur->getId()]);
         }
 
         $lectur->
         setName($r->request->get('lectur_name'))->
         setDescription($r->request->get('lectur_description'));
 
         $errors = $validator->validate($lectur);
 
         // dd(count($errors));
         if (count($errors) > 0){
             foreach($errors as $error) {
                 $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
             }
             $r->getSession()->getFlashBag()->add('lectur_name', $r->request->get('lectur_name'));
             $r->getSession()->getFlashBag()->add('lectur_description', $r->request->get('lectur_description'));
             return $this->redirectToRoute('lectur_edit', ['id'=>$lectur->getId()]);
         }
 
         //creating entity manager sending data to database
         $entityManager = $this->getDoctrine()->getManager();
         //organizing data to be send
         $entityManager->persist($lectur);
         //wrting
         $entityManager->flush();
 
         $r->getSession()->getFlashBag()->add('success', 'Lectur was successfully edited');
 
         return $this->redirectToRoute('lectur_index');
     }
 
     /**
      * @Route("/lectur/delete/{id}", name="lectur_delete", methods= {"POST"})
      */
     public function delete(Request $r, int $id): Response
     {
         $submittedToken = $r->request->get('token');
 
         if (!$this->isCsrfTokenValid('check_csrf_hidden', $submittedToken)) {
             $r->getSession()->getFlashBag()->add('errors', 'Bad talken CSRF');
             return $this->redirectToRoute('lectur_index');
         }
 
         $lectur = $this->getDoctrine()
         ->getRepository(Lectur::class)
         ->find($id);
 
         if ($lectur->getGrades()->count() > 0) {
             $r->getSession()->getFlashBag()->add('errors', 'You cannot deleate the lectur because it has grades' );
             return $this->redirectToRoute('lectur_index');
         }
 
         //creating entity manager sending data to database
         $entityManager = $this->getDoctrine()->getManager();
         //organizing data to be send
         $entityManager->remove($lectur);
         //wrting
         $entityManager->flush();
 
         $r->getSession()->getFlashBag()->add('success', 'Lectur was successfully deleted');
 
         return $this->redirectToRoute('lectur_index');
     }
}

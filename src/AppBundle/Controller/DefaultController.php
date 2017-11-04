<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Classroom;
use AppBundle\Entity\Reservation;
use AppBundle\Form\ReservationType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array());
    }
    
    /**
     * @Route("/list", name="list_classroom")
     */
    public function listAction(Request $request)
    {
        $classrooms = $this->getDoctrine()
        ->getRepository(Classroom::class)
        ->findAll();
        
        return $this->render('default/list.html.twig', array(
            'classrooms' => $classrooms
        ));
    }
    
    /**
     * @Route("/classroom/{id}", name="classroom")
     */
    public function showAction(Classroom $classroom, Request $request)
    {
        $form = null;
        
        $reservations = $classroom->getReservations();
        
        if($user = $this->getUser()){
            
            $reservation = new Reservation();
            $reservation->setClassroom($classroom);
            $reservation->setUser($user);
            
            $form = $this->createForm(ReservationType::class, $reservation);
            $form->handleRequest($request);
            
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($reservation);
                $em->flush();

                $this->redirectToRoute('classroom', array('id' => $classroom->getId()));
            }
        }
        
        $timetableModel = array(
            'monday' => array(
                array('start' => 2, 'duration' => 3),
                array('start' => 5, 'duration' => 1),
                array('start' => 8, 'duration' => 2)
            ),
            'tuesday' => array(
                array('start' => 1, 'duration' => 1),
                array('start' => 4, 'duration' => 1),
                array('start' => 6, 'duration' => 1)
            ),
            'wednesday' => array(
                array('start' => 1, 'duration' => 3),
                array('start' => 4, 'duration' => 3),
                array('start' => 8, 'duration' => 2)
            ),
            'thursday' => array(
                array('start' => 1, 'duration' => 5),
                array('start' => 6, 'duration' => 5)
            ),
            'friday' => array(
                array('start' => 3, 'duration' => 2),
                array('start' => 7, 'duration' => 1),
                array('start' => 9, 'duration' => 3)
            )
        );
   
        
        return $this->render('default/show.html.twig', array(
            'classroom' => $classroom,
            'form' => is_null($form) ? $form : $form->createView(),
            'timetable_model' => $timetableModel
        ));
    }
}
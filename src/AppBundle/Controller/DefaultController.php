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
        
        $timetableModel = $this->prepareTimetableModel($reservations);
        
        return $this->render('default/show.html.twig', array(
            'classroom' => $classroom,
            'form' => is_null($form) ? $form : $form->createView(),
            'timetable_model' => $timetableModel
        ));
    }
    
    private function prepareTimetableModel($reservations){
        
        $timetableModel = array(
            'monday' => array(),
            'tuesday' => array(),
            'wednesday' => array(),
            'thursday' => array(),
            'friday' => array()
        );
        
        foreach($reservations as $reservation){
            
            // Jednostka jest polozenie komorki nie zas sama godzina
            $start = ((int)(str_replace(array(':00'),'',$reservation->getTimeFrom()))) - 7;
            $end = ((int)(str_replace(array(':00'),'',$reservation->getTimeTo()))) - 7;
            $duration = $end - $start;
            
            // Konwertuj polski dzien na angielski
            $day = '';
            switch ($reservation->getDay()) {
                case 'Poniedziałek':
                    $day = 'monday';
                    break;
                case 'Wtorek':
                    $day = 'tuesday';
                    break;
                case 'Środa':
                    $day = 'wednesday';
                    break;
                case 'Czwartek':
                    $day = 'thursday';
                    break;
                case 'Piątek':
                    $day = 'friday';
                    break;
            }
            
            array_push($timetableModel[$day], array('start' => $start, 'duration' => $duration));
            
        }
        
        // Sortuj rezerwacje po godzinie ich zaczecia
        foreach($timetableModel as $tm) {
            usort($tm, function($a, $b) {
                return $a['start'] - $b['start'];
            });
        }
        
        return $timetableModel;
    }
}
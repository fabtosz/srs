<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Classroom;
use AppBundle\Entity\Reservation;
use AppBundle\Form\ReservationType;
use \DateTime;

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
    
    /* * ("/classroom/{id}/week/{week}", name="classroom", defaults={"week"=null})*/
    
    /**
     * @Route("/classroom/{id}", name="classroom")
     */
    public function showAction(Classroom $classroom, Request $request)
    {
        $form = null;
       
        if($request->query->get('week') == null) {
            $now = new DateTime();
            $currentWeekNumber = $now->format("W");
        } else {
            $currentWeekNumber = $request->query->get('week');
        }
        
        $reservations = $this->getDoctrine()
        ->getRepository(Reservation::class)
        ->findAllWeekNumber($classroom->getId(), $currentWeekNumber);
        
        $datesOfWeeks = array();
        for($i=40; $i<=52; $i++){
            $dateBegin = new DateTime(date('d-m-Y',strtotime('2017W'.$i)));
            array_push($datesOfWeeks, $dateBegin->format('d-m-Y'));
            $dateEnd = $dateBegin->modify('+4 day');
            array_push($datesOfWeeks, $dateEnd->format('d-m-Y'));
        }
        dump($datesOfWeeks);
        //Data poczatku roku 2017
        $dateBeginYear = new DateTime(date('d-m-Y',strtotime('2017W01')));
        
        //Aktualny tydzień
        $dto = new DateTime();
        //$currentDate = $dto->setISODate(2017, $currentWeekNumber)->format('d F Y');
        $currentDate = $dto->setISODate(2017, $currentWeekNumber);
        
        //dump($datesOfWeeks); //Do zrbienia linków tygodni
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
            'timetable_model' => $timetableModel,
            'datesOfWeeks' => $datesOfWeeks,
            'currentDate' => $currentDate,
            'dateBeginYear' => $dateBeginYear,
            'currentWeekNumber' => $currentWeekNumber
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
            
            $start = ($reservation->getHour()->format('H')) - 7;
            $duration = $reservation->getDuration();
            $day = strtolower($reservation->getDate()->format('l'));
            
            array_push($timetableModel[$day], array('start' => $start, 'duration' => $duration));
            
        }
        
        return $timetableModel;
        
    }
}
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
        //Data poczatku roku 2017
        $dateBeginYear = new DateTime(date('d-m-Y',strtotime('2017W01')));
        
        //Aktualny tydzień
        $dto = new DateTime();
        //$currentDate = $dto->setISODate(2017, $currentWeekNumber)->format('d F Y');
        $currentDate = $dto->setISODate(2017, $currentWeekNumber);
        
        $timetableModel = $this->prepareTimetableModel($reservations);
        
        if($user = $this->getUser()){
            
            $reservation = new Reservation();
            $reservation->setClassroom($classroom);
            $reservation->setUser($user);
            
            $form = $this->createForm(ReservationType::class, $reservation);
            $form->handleRequest($request);
            
            if($form->isValid() && $this->validate($reservation, $timetableModel, $classroom)){
                $em = $this->getDoctrine()->getManager();
                $em->persist($reservation);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Zarezerwowano salę!'
                );
                
                return $this->redirectToRoute('classroom', array('id' => $classroom->getId()));
            }
        }
        
        
       
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
    
    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository(Reservation::class)->find($id);
        
        $classroomId = $reservation->getClassroom()->getId();
        
        if (!$reservation) {
            throw $this->createNotFoundException(
                'No reservation found for id '.$id
            );
        }

        $em->remove($reservation);
        $em->flush();

        $this->addFlash(
                'warning',
                'Usunięto rezerwację.'
            );
        
        return $this->redirectToRoute('classroom', array('id' => $classroomId));
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
            
            $id = $reservation->getId();
            $start = ($reservation->getHour()->format('H')) - 7;
            $duration = $reservation->getDuration();
            $day = strtolower($reservation->getDate()->format('l'));
            /*** Dodatkowe informacje */
            $overload = $reservation->getOverload();
            $genre = $reservation->getGenre();
            $dateAt = $reservation->getDate();
            $user = $reservation->getUser();
            $title = $reservation->getTitle();

            array_push($timetableModel[$day], array(
                'id' => $id,
                'start' => $start, 
                'user' => $user,
                'title' => $title,
                'duration' => $duration, 
                'overload' => $overload,
                'genre' => $genre,
                ));
            
        }
        
        return $timetableModel;
        
    }
    
    private function validate($toValidate, $model, $classroom){
        
        $day = strtolower($toValidate->getDate()->format('l'));
        
        if($day == 'saturday' || $day == 'sunday'){
            $this->addFlash(
                'danger',
                'Nie można rezerwować w sobotę i niedzielę.'
            );
            return 0;
        }
        
        $startHour = $toValidate->getHour()->format('H') - 7;
        $duration = $toValidate->getDuration();
        $overload = $toValidate->getOverload();
        
        //Sprawdż czy czas nie przekracza limitu w planie
        $availbaleHours = 14 - $startHour;
        if($duration > $availbaleHours){
            $this->addFlash(
                'danger',
                'Nie ma tyle dostępnych godzin w planie.'
            );
            return 0;
        }
        
        //Sprawdź czy godzina rozpoczęcia nie jest zajęta
        foreach ($model[$day] as $key => $value){
            if($value['start'] == $startHour){
                $this->addFlash(
                    'danger',
                    'Na tę godzinę już jest zajęta sala.'
                );
                return 0;
            }
        }
        
        //Wypełnij szkic planu rezerwacji
        $plan = array(0,0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($model[$day] as $key => $value){
            for($i = $value['start']; $i < $value['start'] + $value['duration']; $i++){
                $plan[$i-1] = 1;
            }
        }
        
        //Sprawdz czy nowa rezerwacja nie zaczyna sie w trakcie trwania innej rezerwacji
        for($i = $startHour; $i < $startHour + $duration; $i++){
            if($plan[$i-1] != 0){
                $this->addFlash(
                    'danger',
                    'O podanym czasie trwa inna rezerwacja.'
                );
                return 0;
            }
        }
        
        //Sprawdz obciazenie
        if($overload > $classroom->getOverload()){
            $this->addFlash(
                'danger',
                'Przekroczono obciążenie. Sala nie posiada takiej ilości miejsc.'
            );
            return 0;
        }
        
        return 1;
    }
}
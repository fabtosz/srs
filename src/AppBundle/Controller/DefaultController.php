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
        
        return $this->render('default/show.html.twig', array(
            'classroom' => $classroom,
            'form' => is_null($form) ? $form : $form->createView()
        ));
        /*
        $reservation = new Reservation();
        $reservation->setClassroom($classroom);

        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();

            $this->redirectToRoute('classroom', array('id' => $classroom->getId()));
        }

        return $this->render('default/show.html.twig', array(
            'classroom' => $classroom,
            'form' => $form->createView()
        ));
        */
    }
}
/*
public function showAction(Post $post, Request $request)
    {   
        $form = null;
        
        //jesli uzytkownik jest zalogowany
        if($user = $this->getUser()){
            
            $comment = new Comment();
            $comment->setPost($post);
        
            $comment->setUser($user);
            
            $form = $this->createForm(CommentType::class, $comment);
        
            $form->handleRequest($request);
        
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();
                $this->addFlash('success', 'Komentarz został dodany');
                $this->redirectToRoute('article', array('id' => $post->getId()));
            }
        }
        
        
        return $this->render('default/show.html.twig', array(
            'post' => $post,
            'form' => is_null($form) ? $form : $form->createView()
        ));
    }
 *  */

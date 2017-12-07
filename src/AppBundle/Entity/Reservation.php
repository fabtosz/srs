<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @var
    * 
    * @ORM\ManyToOne(targetEntity="Classroom", inversedBy="reservations")     
    */
    private $classroom;
    
    /**
     * @var
     * 
     * @ORM\ManyToOne(targetEntity="User", inversedBy="reservations")   
     * @ORM\JoinColumn(name="user_id", nullable=true)  
     */
    private $user;
    
    /************************************************/
    /**
     * @var date
     *
     * @ORM\Column(name="data", type="date")
     */
    private $date;
    /************************************************/
    /************************************************/
    /**
     * @var time
     *
     * @ORM\Column(name="hour", type="time")
     */
    private $hour;
    /************************************************/
    /************************************************/
    /**
     *
     * @ORM\Column(name="duration", type="smallint")
     */
    private $duration;
    /************************************************/
    /************************************************/
    /**
     *
     * @ORM\Column(name="genre", type="string")
     */
    private $genre;
    /************************************************/
    /************************************************/
    /**
     *
     * @ORM\Column(name="overload", type="smallint")
     */
    private $overload;
    /************************************************/
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set classroom
     *
     * @param \AppBundle\Entity\Classroom $classroom
     *
     * @return Reservation
     */
    public function setClassroom(\AppBundle\Entity\Classroom $classroom = null)
    {
        $this->classroom = $classroom;

        return $this;
    }

    /**
     * Get classroom
     *
     * @return \AppBundle\Entity\Classroom
     */
    public function getClassroom()
    {
        return $this->classroom;
    }
    
    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Reservation
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;
        return $this;
    }
    
    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /***************************************/
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }
    /***************************************/
    /***************************************/
    public function setHour($hour)
    {
        $this->hour = $hour;

        return $this;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getHour()
    {
        return $this->hour;
    }
    /***************************************/
    /***************************************/
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }
    /***************************************/
    /***************************************/
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }
    /***************************************/
    /***************************************/
    public function setOverload($overload)
    {
        $this->overload = $overload;

        return $this;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getOverload()
    {
        return $this->overload;
    }
    /***************************************/
}

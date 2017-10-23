<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var
     * 
     * @ORM\OneToMany(targetEntity="Reservation", mappedBy="user")     
     */
    private $reservations;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Add reservation
     *
     * @param \AppBundle\Entity\Reservation $reservation
     *
     * @return User
     */
    public function addReservation(\AppBundle\Entity\Reservation $reservation)
    {
        $this->reservations[] = $reservation;
        return $this;
    }
    /**
     * Remove reservation
     *
     * @param \AppBundle\Entity\Reservation $reservation
     */
    public function removeReservation(\AppBundle\Entity\Reservation $reservation)
    {
        $this->reservations->removeElement($reservation);
    }
    /**
     * Get reservations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReservations()
    {
        return $this->reservations;
    }
}
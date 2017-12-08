<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ReservationRepository extends EntityRepository
{
    public function findAllWeekNumber($id, $currentWeek)
    {
        $parameters = array(
            'currentWeek' => $currentWeek,
            'id' => $id,
        );
        
        return $this->getEntityManager()
            ->createQuery(
                "SELECT r FROM AppBundle:Reservation r WHERE r.classroom = :id AND WEEK(r.date, 3)= :currentWeek ORDER BY r.hour"
            )
            ->setParameters($parameters)
            ->getResult();
    }
}
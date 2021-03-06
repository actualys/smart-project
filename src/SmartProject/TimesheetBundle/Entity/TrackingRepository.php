<?php

namespace SmartProject\TimesheetBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TrackingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TrackingRepository extends EntityRepository
{
    /**
     * @param UserInterface $user
     * @param \DateTime     $date
     *
     * @return string
     */
    public function getTotalDurationForDate(UserInterface $user, \DateTime $date)
    {
        $entities = $this->createQueryBuilder('tr')
          ->select('tr.duration')
          ->join('tr.task', 'ta')
          ->join('ta.timesheet', 'ti')
          ->where('ti.user = :user')
          ->andWhere('tr.date = :date')
          ->setParameter(':user', $user)
          ->setParameter(':date', $date->format('Y-m-d'))
          ->getQuery()
          ->execute();

        if ($entities) {
            $total = 0;

            foreach ($entities as $entity) {
                $total += $entity['duration'];
            }

            return $total;
        } else {
            return '';
        }
    }
}

<?php

namespace SmartProject\TimesheetBundle\Controller;

use Doctrine\ORM\EntityManager;
use SmartProject\TimesheetBundle\Entity\Timesheet;
use SmartProject\TimesheetBundle\Entity\TimesheetRepository;
use SmartProject\TimesheetBundle\Entity\Tracking;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SmartProject\TimesheetBundle\Entity\Task;
use Symfony\Component\HttpFoundation\Response;

/**
 * Timeline controller.
 *
 * @Route("/timeline")
 */
class TimelineController extends Controller
{
    const COOKIE_MODE = 'timeline_mode';

    /**
     * Lists all Task entities.
     *
     * @Route("/", name="timeline")
     * @Route("/{mode}", name="timeline_today")
     * @Route("/{mode}/{date}", name="timeline_mode")
     * @Method("GET")
     */
    public function indexAction(Request $request, $mode = null, $date = null)
    {
        if (null === $mode || 'auto' === $mode) {
            $mode = $request->cookies->get(self::COOKIE_MODE, 'day');
        }

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        if (null !== $date) {
            $date = new \DateTime($date);
        } else {
            $date = new \DateTime('now');
        }

        $user = $this->getUser();

        /** @var TimesheetRepository $timesheetRepository */
        $timesheetRepository = $em->getRepository('SmartProjectTimesheetBundle:Timesheet');
        /** @var Timesheet $timesheet */
        $timesheet = $timesheetRepository->findByUser($user, $date);

        if (null === $timesheet) {
            $timesheet = $timesheetRepository->createForUser($user, $date);
        }

        $days = array();

        if ($mode == 'week') {
            /** @var \DateTime $dateStart */
            $dateStart = clone $timesheet->getDateStart();

            for ($i = 0; $i < 7; $i++) {
                $days[$dateStart->format('Y-m-d')] = array(
                    'trackings'   => array(),
                    'day_off'     => $i >= 5,
                    'fulfillment' => 0,
                );
                $dateStart->add(new \DateInterval('P1D'));
            }
        } else {
            $days[$date->format('Y-m-d')] = array(
                'trackings'   => array(),
                'day_off'     => ($date->format('w') == 6 || $date->format('w') == 0),
                'fulfillment' => 0,
            );
        }

        if ($timesheet->getId()) {
            $queryBuilder = $em->createQueryBuilder();
            $query        = $queryBuilder->select('tracking, task')
              ->from('SmartProjectTimesheetBundle:Tracking', 'tracking')
              ->join('tracking.task', 'task')
              ->where('task.timesheet = :timesheet')
              ->setParameter(':timesheet', $timesheet)
              ->orderBy('tracking.date', 'asc')
              ->addOrderBy('task.id', 'asc')
              ->getQuery();
            $entities     = $query->execute();
        } else {
            $entities = array();
        }

        /** @var Tracking $tracking */
        foreach ($entities as $tracking) {
            $date_formatted = $tracking->getDate()->format('Y-m-d');

            if ($mode != 'week' && $date_formatted != $date->format('Y-m-d')) {
                continue;
            }

            $days[$date_formatted]['trackings'][] = $tracking;
            $days[$date_formatted]['day_off']     = false;
            $days[$date_formatted]['fulfillment'] += $tracking->getDuration();
        }

        $parameters = array(
            'mode'         => $mode,
            'timesheet'    => $timesheet,
            'days'         => $days,
            'date'         => $date,
            'day_duration' => 7,
        );
        $content    = $this->renderView('SmartProjectTimesheetBundle:Timeline:index.html.twig', $parameters);
        $response   = new Response($content);
        $cookie     = new Cookie(self::COOKIE_MODE, $mode, 0, $request->getBaseUrl());
        $response->headers->setCookie($cookie);

        return $response;
    }
}

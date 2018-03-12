<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CronjobSchedule;
use Hashids\Hashids;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Execution controller.
 *
 * @Route("job")
 */
class ExecutionController extends Controller
{
    const SCHEDULES_TO_CALCULATE = 5;

    /**
     * Mark a job as started
     *
     * @Route("/{hashid}/start", name="job_start")
     * @Method("GET")
     */
    public function startAction(Request $request)
    {
        $hashid = $request->get('hashid');
        $em = $this->getDoctrine()->getManager();

        $id = $this->decodeId($hashid);

        $executionDate = new \DateTime();
        $executionDate->setTime(date('G'), (int)date('i'), 0);

        /** @var CronjobSchedule|null $cronSchedule */
        $cronSchedule = $em->getRepository('AppBundle:CronjobSchedule')->findScheduledByJobAndTime($id, $executionDate);
        if (null === $cronSchedule) {
            // @FIXME: log this
            return new Response('KO');

        }
        $cronSchedule
            ->setStartedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime())
            ->setStatus(CronjobSchedule::STATUS_STARTED);
        $em->persist($cronSchedule);
        $em->flush();



        return new Response('OK');
    }

    /**
     * Mark a job as complete
     *
     * @Route("/{hashid}/complete", name="job_complete")
     * @Method("GET")
     */
    public function completeAction(Request $request)
    {
        $hashid = $request->get('hashid');
        $em = $this->getDoctrine()->getManager();

        $id = $this->decodeId($hashid);

        $executionDate = new \DateTime();
        $executionDate->setTime(date('G'), (int)date('i'), 0);

        /** @var CronjobSchedule|null $cronSchedule */
        $cronSchedule = $em->getRepository('AppBundle:CronjobSchedule')->findRunningByJob($id);
        if (null === $cronSchedule) {
            // @FIXME: log this
            return new Response('KO');

        }
        $cronSchedule
            ->setEndedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime())
            ->setStatus(CronjobSchedule::STATUS_ENDED);
        $em->persist($cronSchedule);
        $em->flush();

        return new Response('OK');
    }

    /**
     * Marks a job as erroneous
     *
     * @Route("/{hashid}/error", name="job_error")
     * @Method("GET")
     */
    public function errorAction(Request $request)
    {
        $hashid = $request->get('hashid');
        $em = $this->getDoctrine()->getManager();

        $id = $this->decodeId($hashid);

        $executionDate = new \DateTime();
        $executionDate->setTime(date('G'), (int)date('i'), 0);

        /** @var CronjobSchedule|null $cronSchedule */
        $cronSchedule = $em->getRepository('AppBundle:CronjobSchedule')->findRunningByJob($id);
        if (null === $cronSchedule) {
            // @FIXME: log this
            return new Response('KO');

        }
        $cronSchedule
            ->setEndedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime())
            ->setStatus(CronjobSchedule::STATUS_ERROR);
        $em->persist($cronSchedule);
        $em->flush();

        return new Response('OK');
    }

    /**
     * @param $hashId
     * @return int
     */
    private function decodeId($hashId)
    {
        $hashIdDecode = new Hashids($this->getParameter('secret'), $this->getParameter('hashid_length'));
        $hashIdArray = $hashIdDecode->decode($hashId);
        $id = (int)end($hashIdArray);

        return $id;
    }
}

<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cronjob;
use AppBundle\Entity\CronjobSchedule;
use Cron\CronExpression;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\CronjobType;

/**
 * Cronjob controller.
 *
 * @Route("cronjob")
 */
class CronjobController extends Controller
{
    const SCHEDULES_TO_CALCULATE = 5;
    const SCHEDULES_TO_DISPLAY = 5;

    /**
     * Lists all cronjob entities.
     *
     * @Route("/", name="cronjob_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $cronjobs = $em->getRepository('AppBundle:Cronjob')->findAll();

        return $this->render('cronjob/index.html.twig', array(
            'cronjobs' => $cronjobs,
        ));
    }

    /**
     * Creates a new cronjob entity.
     *
     * @Route("/new", name="cronjob_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $cronjob = new Cronjob();
        $form = $this->createForm(CronjobType::class, $cronjob);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // Add additional scheduled launchs
            $cronExp = CronExpression::factory($cronjob->getCronExpression());
            $cronSchedules = [];
            for ($i = 0; $i < self::SCHEDULES_TO_CALCULATE; $i++) {
                $schedule = new CronjobSchedule();
                $schedule
                    ->setCronjob($cronjob)
                    ->setScheduledAt($cronExp->getNextRunDate('now', $i));
                $cronjob->addSchedule($schedule);
                $cronSchedules[] = $schedule;
            }
            $em->persist($cronjob);
            foreach ($cronSchedules as $cronSchedule) {
                $em->persist($cronSchedule);
            }
            $em->flush();

            return $this->redirectToRoute('cronjob_show', array('id' => $cronjob->getId()));
        }

        return $this->render('cronjob/new.html.twig', array(
            'cronjob' => $cronjob,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a cronjob entity.
     *
     * @Route("/{id}", name="cronjob_show")
     * @Method("GET")
     */
    public function showAction(Cronjob $cronjob)
    {
        $deleteForm = $this->createDeleteForm($cronjob);

        $nextSchedules = [];
        $cronExpr = CronExpression::factory($cronjob->getCronExpression());
        for ($i = 0; $i < self::SCHEDULES_TO_CALCULATE + 1; $i++) {
            $nextSchedules[] = $cronExpr->getNextRunDate('now', $i);
        }

        return $this->render('cronjob/show.html.twig', array(
            'cronjob' => $cronjob,
            'next_schedules' => $nextSchedules,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a cronjob schedules.
     *
     * @Route("/{id}/schedules", name="cronjob_schedules")
     * @Method("GET")
     */
    public function schedulesAction(Cronjob $cronjob)
    {
        $em = $this->getDoctrine()->getManager();

        $schedules = $em->getRepository('AppBundle:CronjobSchedule')->findByCronjob($cronjob);

        return $this->render('cronjob/schedules.html.twig', array(
            'cronjob' => $cronjob,
            'schedules' => $schedules,
        ));
    }

    /**
     * Displays a form to edit an existing cronjob entity.
     *
     * @Route("/{id}/edit", name="cronjob_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Cronjob $cronjob)
    {
        $deleteForm = $this->createDeleteForm($cronjob);
        $editForm = $this->createForm(CronjobType::class, $cronjob);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cronjob_edit', array('id' => $cronjob->getId()));
        }

        return $this->render('cronjob/edit.html.twig', array(
            'cronjob' => $cronjob,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a cronjob entity.
     *
     * @Route("/{id}", name="cronjob_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Cronjob $cronjob)
    {
        $form = $this->createDeleteForm($cronjob);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cronjob);
            $em->flush();
        }

        return $this->redirectToRoute('cronjob_index');
    }

    /**
     * Creates a form to delete a cronjob entity.
     *
     * @param Cronjob $cronjob The cronjob entity
     *
     * @return FormInterface The form
     */
    private function createDeleteForm(Cronjob $cronjob)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cronjob_delete', array('id' => $cronjob->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

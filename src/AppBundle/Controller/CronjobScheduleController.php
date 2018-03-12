<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CronjobSchedule;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Cronjobschedule controller.
 *
 * @Route("cronjobschedule")
 */
class CronjobScheduleController extends Controller
{
    /**
     * Lists all cronjobSchedule entities.
     *
     * @Route("/", name="cronjobschedule_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $cronjobSchedules = $em->getRepository('AppBundle:CronjobSchedule')->findAll();

        return $this->render('cronjobschedule/index.html.twig', array(
            'cronjobSchedules' => $cronjobSchedules,
        ));
    }

    /**
     * Creates a new cronjobSchedule entity.
     *
     * @Route("/new", name="cronjobschedule_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $cronjobSchedule = new Cronjobschedule();
        $form = $this->createForm('AppBundle\Form\CronjobScheduleType', $cronjobSchedule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cronjobSchedule);
            $em->flush();

            return $this->redirectToRoute('cronjobschedule_show', array('id' => $cronjobSchedule->getId()));
        }

        return $this->render('cronjobschedule/new.html.twig', array(
            'cronjobSchedule' => $cronjobSchedule,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a cronjobSchedule entity.
     *
     * @Route("/{id}", name="cronjobschedule_show")
     * @Method("GET")
     */
    public function showAction(CronjobSchedule $cronjobSchedule)
    {
        $deleteForm = $this->createDeleteForm($cronjobSchedule);

        return $this->render('cronjobschedule/show.html.twig', array(
            'cronjobSchedule' => $cronjobSchedule,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing cronjobSchedule entity.
     *
     * @Route("/{id}/edit", name="cronjobschedule_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CronjobSchedule $cronjobSchedule)
    {
        $deleteForm = $this->createDeleteForm($cronjobSchedule);
        $editForm = $this->createForm('AppBundle\Form\CronjobScheduleType', $cronjobSchedule);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cronjobschedule_edit', array('id' => $cronjobSchedule->getId()));
        }

        return $this->render('cronjobschedule/edit.html.twig', array(
            'cronjobSchedule' => $cronjobSchedule,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a cronjobSchedule entity.
     *
     * @Route("/{id}", name="cronjobschedule_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CronjobSchedule $cronjobSchedule)
    {
        $form = $this->createDeleteForm($cronjobSchedule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cronjobSchedule);
            $em->flush();
        }

        return $this->redirectToRoute('cronjobschedule_index');
    }

    /**
     * Creates a form to delete a cronjobSchedule entity.
     *
     * @param CronjobSchedule $cronjobSchedule The cronjobSchedule entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CronjobSchedule $cronjobSchedule)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cronjobschedule_delete', array('id' => $cronjobSchedule->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

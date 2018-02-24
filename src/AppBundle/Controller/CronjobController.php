<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cronjob;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Cronjob controller.
 *
 * @Route("cronjob")
 */
class CronjobController extends Controller
{
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
        $form = $this->createForm('AppBundle\Form\CronjobType', $cronjob);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cronjob);
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

        return $this->render('cronjob/show.html.twig', array(
            'cronjob' => $cronjob,
            'delete_form' => $deleteForm->createView(),
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
        $editForm = $this->createForm('AppBundle\Form\CronjobType', $cronjob);
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
     * @return \Symfony\Component\Form\Form The form
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

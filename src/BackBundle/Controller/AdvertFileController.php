<?php

namespace BackBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use MainBundle\Entity\AdvertFile;
use BackBundle\Form\AdvertFileType;

/**
 * AdvertFile controller.
 *
 */
class AdvertFileController extends Controller
{
    /**
     * Lists all AdvertFile entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $advertFiles = $em->getRepository('MainBundle:AdvertFile')->findAll();

        return $this->render('BackBundle:AdvertFile:index.html.twig', array(
            'advertFiles' => $advertFiles,
        ));
    }

    /**
     * Creates a new AdvertFile entity.
     *
     */
    public function newAction(Request $request)
    {
        $advertFile = new AdvertFile();
        $form = $this->createForm('BackBundle\Form\AdvertFileType', $advertFile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($advertFile);
            $em->flush();

            return $this->redirectToRoute('back_advertfile_show', array('id' => $advertFile->getId()));
        }

        return $this->render('BackBundle:AdvertFile:new.html.twig', array(
            'advertFile' => $advertFile,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdvertFile entity.
     *
     */
    public function showAction(AdvertFile $advertFile)
    {
        $deleteForm = $this->createDeleteForm($advertFile);

        return $this->render('BackBundle:AdvertFile:show.html.twig', array(
            'advertFile' => $advertFile,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdvertFile entity.
     *
     */
    public function editAction(Request $request, AdvertFile $advertFile)
    {
        $deleteForm = $this->createDeleteForm($advertFile);
        $editForm = $this->createForm('BackBundle\Form\AdvertFileType', $advertFile);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($advertFile);
            $em->flush();

            return $this->redirectToRoute('back_advertfile_edit', array('id' => $advertFile->getId()));
        }

        return $this->render('BackBundle:AdvertFile:edit.html.twig', array(
            'advertFile' => $advertFile,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdvertFile entity.
     *
     */
    public function deleteAction(Request $request, AdvertFile $advertFile)
    {
        $form = $this->createDeleteForm($advertFile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($advertFile);
            $em->flush();
        }

        return $this->redirectToRoute('back_advertfile_index');
    }

    /**
     * Creates a form to delete a AdvertFile entity.
     *
     * @param AdvertFile $advertFile The AdvertFile entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdvertFile $advertFile)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('back_advertfile_delete', array('id' => $advertFile->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

<?php

namespace BackBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use MainBundle\Entity\Whishlist;
use BackBundle\Form\WhishlistType;

/**
 * Whishlist controller.
 *
 */
class WhishlistController extends BaseController
{
    /**
     * Lists all Whishlist entities.
     *
     */
    public function indexAction(Request $request, $page)
    {
        $this->grantAccessUserSecurity();
        $em = $this->getDoctrine()->getManager();

        $allWhishlists = $em->getRepository('MainBundle:Whishlist')
            ->findAll();


        if(empty($page)){
            $page = $request->query->getInt('page', 1);
        }

        $paginator = $this->get('knp_paginator');
        $whishlists = $paginator->paginate(
            $allWhishlists,
            $page,
            5
        );

        return $this->render('BackBundle:Whishlist:index.html.twig', array(
            'whishlists' => $whishlists,
            'page' => $page
        ));
    }

    /**
     * Creates a new Whishlist entity.
     *
     */
    public function newAction(Request $request)
    {
        $this->grantAccessUserSecurity();
        $whishlist = new Whishlist();
        $form = $this->createForm('BackBundle\Form\WhishlistType', $whishlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($whishlist);
            $em->flush();

            return $this->redirectToRoute('back_whishlist_show', array('id' => $whishlist->getId()));
        }

        return $this->render('BackBundle:Whishlist:new.html.twig', array(
            'whishlist' => $whishlist,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Whishlist entity.
     *
     */
    public function showAction(Whishlist $whishlist)
    {
        $this->grantAccessUserSecurity();
        $deleteForm = $this->createDeleteForm($whishlist);

        return $this->render('BackBundle:Whishlist:show.html.twig', array(
            'whishlist' => $whishlist,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Whishlist entity.
     *
     */
    public function editAction(Request $request, Whishlist $whishlist)
    {
        $this->grantAccessUserSecurity();
        $deleteForm = $this->createDeleteForm($whishlist);
        $editForm = $this->createForm('BackBundle\Form\WhishlistType', $whishlist);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($whishlist);
            $em->flush();

            return $this->redirectToRoute('back_whishlist_edit', array('id' => $whishlist->getId()));
        }

        return $this->render('BackBundle:Whishlist:edit.html.twig', array(
            'whishlist' => $whishlist,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Whishlist entity.
     *
     */
    public function deleteAction(Request $request, Whishlist $whishlist)
    {
        $this->grantAccessUserSecurity();
        $form = $this->createDeleteForm($whishlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($whishlist);
            $em->flush();
        }

        return $this->redirectToRoute('back_whishlist_index');
    }

    /**
     * Creates a form to delete a Whishlist entity.
     *
     * @param Whishlist $whishlist The Whishlist entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Whishlist $whishlist)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('back_whishlist_delete', array('id' => $whishlist->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

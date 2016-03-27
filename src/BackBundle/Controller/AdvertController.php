<?php

namespace BackBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use MainBundle\Entity\AdvertFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use MainBundle\Entity\Advert;
use BackBundle\Form\AdvertType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Advert controller.
 *
 */
class AdvertController extends BaseController
{
    /**
     * Lists all Advert entities.
     *
     */
    public function indexAction(Request $request, $page = null)
    {
        $this->grantAccessUserSecurity();

        $em = $this->getDoctrine()->getManager();

        $allAdverts = $em->getRepository('MainBundle:Advert')->findBy(array(),array('createdAt' => 'DESC'));

        if (null === $allAdverts) {
            throw new NotFoundHttpException("Aucunes annonces.");
        }

        if(empty($page)){
            $page = $request->query->getInt('page', 1);
        }

        $paginator = $this->get('knp_paginator');
        $adverts = $paginator->paginate(
            $allAdverts,
            $page,
            4
        );

        return $this->render('BackBundle:Advert:index.html.twig', array(
            'adverts' => $adverts,
        ));
    }

    /**
     * Creates a new Advert entity.
     *
     */
    public function newAction(Request $request)
    {
        $this->grantAccessUserSecurity();

        $advert = new Advert();
        $form = $this->createForm('BackBundle\Form\AdvertType', $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();

            return $this->redirectToRoute('back_advert_show', array('id' => $advert->getId()));
        }

        return $this->render('BackBundle:Advert:new.html.twig', array(
            'advert' => $advert,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Advert entity.
     *
     */
    public function showAction(Advert $advert)
    {
        $this->grantAccessUserSecurity();

        $deleteForm = $this->createDeleteForm($advert);

        return $this->render('BackBundle:Advert:show.html.twig', array(
            'advert' => $advert,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Advert entity.
     *
     */
    public function editAction(Request $request, Advert $advert)
    {
        $this->grantAccessUserSecurity();

        $deleteForm = $this->createDeleteForm($advert);
        $editForm = $this->createForm('BackBundle\Form\AdvertType', $advert);
        $editForm->handleRequest($request);
        //Count Files
        //Current files and tags stored with Advert
        $currentFileAdvert = count($advert->getFileadverts());
        //Files stored in request / Post
        $advertsv = $request->files->get('advert') ? $request->files->get('advert'): null ;
        //Total Files Count / Sum
        $totalFiles = ($currentFileAdvert + count($advertsv['files']));
        //Check files limit  to 5 files & tags
        if($totalFiles <= 5 && count($advertsv['files']) <= 5 ){
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($advert);
                $em->flush();
                return $this->redirectToRoute('back_advert_edit', array('id' => $advert->getId()));
            }
        }else{
            $session = $request->getSession();
            $session->getFlashBag()->add('warning', 'Vous avez atteind la limite d\'upload de 5 fichiers');
        }

        return $this->render('BackBundle:Advert:edit.html.twig', array(
            'advert' => $advert,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Advert entity.
     *
     */
    public function deleteAction(Request $request, Advert $advert)
    {
        $this->grantAccessUserSecurity();

        $form = $this->createDeleteForm($advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($advert);
            $em->flush();
        }

        return $this->redirectToRoute('back_advert_index');
    }

    /**
     * Creates a form to delete a Advert entity.
     *
     * @param Advert $advert The Advert entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Advert $advert)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('back_advert_delete', array('id' => $advert->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function activateAdvertByTokenAction(Request $request, $token)
    {
        $this->grantAccessUserSecurity();

        if (null === $token) {
            throw $this->createNotFoundException('Unable to find Advert entity.');
        }

        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('MainBundle:Advert')->findOneBy(array('token' => $token));

        if (!$advert) {
            throw $this->createNotFoundException('Unable to find Advert entity.');
        }

        $activateForm = $this->createActivateForm($advert);
        $activateForm->handleRequest($request);

        if ($request->getMethod() == 'POST') {

            $advert->setIsActivated(1);
            $em->persist($advert);
            $em->flush();
            return $this->redirectToRoute('back_advert_activated', array('id' => $advert->getId()));
        }

        return $this->render('BackBundle:Advert:activate-advert.html.twig', array(
            'advert' => $advert,
            'activate_form' => $activateForm->createView(),
        ));
    }

    private function createActivateForm(Advert $advert)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('back_advert_activate_token', array('token' => $advert->getToken())))
            ->setMethod('POST')
            ->getForm()
            ;
    }

    public function activatedAction(Advert $advert)
    {
        $this->grantAccessUserSecurity();
        if (!$advert) {
            throw $this->createNotFoundException('Unable to find Advert entity.');
        }

        return $this->render('BackBundle:Advert:activated.html.twig', array(
            'advert' => $advert
        ));

    }
}

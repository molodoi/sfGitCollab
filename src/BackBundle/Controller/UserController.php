<?php

namespace BackBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use MainBundle\Entity\User;
use MainBundle\Form\UserType;

/**
 * User controller.
 *
 */
class UserController extends Controller
{
    /**
     * Lists all User entities.
     *
     */
    public function indexAction(Request $request, $page)
    {

        $em = $this->getDoctrine()->getManager();

        $allUsers = $em->getRepository('MainBundle:User')
            ->findAll();

        if(empty($page)){
            $page = $request->query->getInt('page', 1);
        }

        $paginator = $this->get('knp_paginator');
        $users = $paginator->paginate(
            $allUsers,
            $page,
            5
        );

        return $this->render('BackBundle:User:index.html.twig', array(
            'users' => $users,
            'page' => $page
        ));
    }

    /**
     * Creates a new User entity.
     *
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('BackBundle\Form\UserType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('back_user_show', array('id' => $user->getId()));
        }

        return $this->render('BackBundle:User:new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     */
    public function showAction(User $user)
    {

        $deleteForm = $this->createDeleteForm($user);

        return $this->render('BackBundle:User:show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('BackBundle\Form\UserType', $user, array(
            'passwordRequired' => false,
            'lockedRequired' => true
        ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user->setUpdatedAt(new \DateTime());
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('back_user_edit', array('id' => $user->getId()));
        }

        return $this->render('BackBundle:User:edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    /*public function editAction(User $user)
    {
        $editForm = $this->createForm('BackBundle\Form\UserType', $user, array(
            'action' => $this->generateUrl('back_user_update', array('id' => $user->getId())),
            'method' => 'PUT',
            'passwordRequired' => false,
            'lockedRequired' => true
        ));
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('BackBundle:User:edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }*/

    /**
     * Edits an existing User entity.
     *
     */
    public function updateAction(User $user, Request $request)
    {

        $editForm = $this->createForm('BackBundle\Form\UserType', $user, array(
            'action' => $this->generateUrl('back_user_update', array('id' => $user->getId())),
            'method' => 'PUT',
            'passwordRequired' => false,
            'lockedRequired' => true
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);

            /*$em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();*/
            return $this->redirect($this->generateUrl('back_user_edit', array('id' => $user->getId())));
        }
        $deleteForm = $this->createDeleteForm($user);

        return array(
            'user' => $user,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a User entity.
     *
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('back_user_index');
    }

    /**
     * Creates a form to delete a User entity.
     *
     * @param User $user The User entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('back_user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

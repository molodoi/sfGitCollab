<?php

namespace FrontBundle\Controller;

use MainBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function showAction(Request $request, User $user)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User.');
        }

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('MainBundle:User')->findOneById($user->getId());

        return $this->render('FrontBundle:User:show.html.twig', array(
            'user' => $user,
        ));

    }
}